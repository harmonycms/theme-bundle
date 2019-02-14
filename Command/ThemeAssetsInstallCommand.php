<?php

namespace Harmony\Bundle\ThemeBundle\Command;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\AssetsInstallCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ThemeAssetsInstallCommand
 *
 * @package Harmony\Bundle\ThemeBundle\Command
 */
class ThemeAssetsInstallCommand extends Command
{

    /** Constants */
    const THEMES_DIR = 'themes';
    const ASSETS_DIR = 'assets';

    /** @var Filesystem $filesystem */
    protected $filesystem;

    /** @var string $projectDir */
    protected $projectDir;

    /** @var KernelInterface|AbstractKernel $kernel */
    protected $kernel;

    /**
     * ThemeAssetsInstallCommand constructor.
     *
     * @param KernelInterface|AbstractKernel $kernel
     * @param Filesystem                     $filesystem
     * @param string                         $projectDir
     */
    public function __construct(KernelInterface $kernel, Filesystem $filesystem, string $projectDir)
    {
        parent::__construct(null);
        $this->kernel     = $kernel;
        $this->filesystem = $filesystem;
        $this->projectDir = $projectDir;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('theme:assets:install')
            ->setDefinition([
                new InputArgument('target', InputArgument::OPTIONAL, 'The target directory', 'public'),
            ])
            ->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the assets instead of copying it')
            ->addOption('relative', null, InputOption::VALUE_NONE, 'Make relative symlinks')
            ->setDescription('Installs themes web assets under a public directory')
            ->setHelp(<<<'EOT'
The <info>%command.name%</info> command installs theme assets into a given
directory (e.g. the <comment>public</comment> directory).

  <info>php %command.full_name% public</info>

A "themes" directory will be created inside the target directory and the
"assets" directory of each theme will be copied into it.

To create a symlink to each theme instead of copying its assets, use the
<info>--symlink</info> option (will fall back to hard copies when symbolic links aren't possible:

  <info>php %command.full_name% public --symlink</info>

To make symlink relative, add the <info>--relative</info> option:

  <info>php %command.full_name% public --symlink --relative</info>

EOT
            );
    }

    /**
     * Executes the current command.
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws LogicException When this abstract method is not implemented
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $targetArg = rtrim($input->getArgument('target'), '/');
        if (!is_dir($targetArg)) {
            throw new InvalidArgumentException(sprintf('The target directory "%s" does not exist.',
                $input->getArgument('target')));
        }

        // Create the themes directory otherwise symlink will fail.
        $themesDir = $targetArg . DIRECTORY_SEPARATOR . self::THEMES_DIR . DIRECTORY_SEPARATOR;
        $this->filesystem->mkdir($themesDir, 0777);

        $io = new SymfonyStyle($input, $output);
        $io->newLine();
        if ($input->getOption('relative')) {
            $expectedMethod = AssetsInstallCommand::METHOD_RELATIVE_SYMLINK;
            $io->text('Trying to install theme assets as <info>relative symbolic links</info>.');
        } elseif ($input->getOption('symlink')) {
            $expectedMethod = AssetsInstallCommand::METHOD_ABSOLUTE_SYMLINK;
            $io->text('Trying to install theme assets as <info>absolute symbolic links</info>.');
        } else {
            $expectedMethod = AssetsInstallCommand::METHOD_COPY;
            $io->text('Installing theme assets as <info>hard copies</info>.');
        }
        $io->newLine();

        $rows     = [];
        $copyUsed = false;
        $exitCode = 0;

        foreach ($this->kernel->getThemes() as $theme) {
            $originDir = $theme->getPath() . DIRECTORY_SEPARATOR . self::ASSETS_DIR;
            if (!is_dir($originDir)) {
                continue;
            }
            $targetDir = $themesDir . $theme->getShortName();

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $message = sprintf("%s\n-> %s", $theme->getIdentifier(), $targetDir);
            } else {
                $message = $theme->getIdentifier();
            }

            try {
                $this->filesystem->remove($targetDir);

                if (AssetsInstallCommand::METHOD_RELATIVE_SYMLINK === $expectedMethod) {
                    $method = $this->_relativeSymlinkWithFallback($originDir, $targetDir);
                } elseif (AssetsInstallCommand::METHOD_ABSOLUTE_SYMLINK === $expectedMethod) {
                    $method = $this->_absoluteSymlinkWithFallback($originDir, $targetDir);
                } else {
                    $method = $this->_hardCopy($originDir, $targetDir);
                }

                if (AssetsInstallCommand::METHOD_COPY === $method) {
                    $copyUsed = true;
                }

                if ($method === $expectedMethod) {
                    $rows[] = [
                        sprintf('<fg=green;options=bold>%s</>',
                            '\\' === DIRECTORY_SEPARATOR ? 'OK' : "\xE2\x9C\x94" /* HEAVY CHECK MARK (U+2714) */),
                        $message,
                        $method
                    ];
                } else {
                    $rows[] = [
                        sprintf('<fg=yellow;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'WARNING' : '!'),
                        $message,
                        $method
                    ];
                }
            }
            catch (\Exception $e) {
                $exitCode = 1;
                $rows[]   = [
                    sprintf('<fg=red;options=bold>%s</>',
                        '\\' === DIRECTORY_SEPARATOR ? 'ERROR' : "\xE2\x9C\x98" /* HEAVY BALLOT X (U+2718) */),
                    $message,
                    $e->getMessage()
                ];
            }
        }

        $io->table(['', 'Theme', 'Method / Error'], $rows);

        if (0 !== $exitCode) {
            $io->error('Some errors occurred while installing assets.');
        } else {
            if ($copyUsed) {
                $io->note('Some assets were installed via copy. If you make changes to these assets you have to run this command again.');
            }
            $io->success('All assets were successfully installed.');
        }

        return $exitCode;
    }

    /**
     * Try to create relative symlink.
     * Falling back to absolute symlink and finally hard copy.
     *
     * @param string $originDir
     * @param string $targetDir
     *
     * @return string
     */
    private function _relativeSymlinkWithFallback(string $originDir, string $targetDir): string
    {
        try {
            $this->_symlink($originDir, $targetDir, true);
            $method = AssetsInstallCommand::METHOD_RELATIVE_SYMLINK;
        }
        catch (IOException $e) {
            $method = $this->_absoluteSymlinkWithFallback($originDir, $targetDir);
        }

        return $method;
    }

    /**
     * Try to create absolute symlink.
     * Falling back to hard copy.
     *
     * @param string $originDir
     * @param string $targetDir
     *
     * @return string
     */
    private function _absoluteSymlinkWithFallback(string $originDir, string $targetDir): string
    {
        try {
            $this->_symlink($originDir, $targetDir);
            $method = AssetsInstallCommand::METHOD_ABSOLUTE_SYMLINK;
        }
        catch (IOException $e) {
            // fall back to copy
            $method = $this->_hardCopy($originDir, $targetDir);
        }

        return $method;
    }

    /**
     * Creates symbolic link.
     *
     * @param string $originDir
     * @param string $targetDir
     * @param bool   $relative
     *
     * @throws IOException If link can not be created.
     */
    private function _symlink(string $originDir, string $targetDir, bool $relative = false)
    {
        if ($relative) {
            $originDir = $this->filesystem->makePathRelative($originDir, realpath(dirname($targetDir)));
        }
        $this->filesystem->symlink($originDir, $targetDir);
        if (!file_exists($targetDir)) {
            throw new IOException(sprintf('Symbolic link "%s" was created but appears to be broken.', $targetDir), 0,
                null, $targetDir);
        }
    }

    /**
     * Copies origin to target.
     *
     * @param string $originDir
     * @param string $targetDir
     *
     * @return string
     */
    private function _hardCopy(string $originDir, string $targetDir): string
    {
        $this->filesystem->mkdir($targetDir, 0777);
        // We use a custom iterator to ignore VCS files
        $this->filesystem->mirror($originDir, $targetDir, Finder::create()->ignoreDotFiles(false)->in($originDir));

        return AssetsInstallCommand::METHOD_COPY;
    }
}
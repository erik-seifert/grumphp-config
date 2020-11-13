<?php

namespace shaquer\GrumPHPTwigCS;

use Symfony\Component\OptionsResolver\OptionsResolver;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\AbstractExternalTask;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Runner\TaskResult;


class TwigCS extends AbstractExternalTask {

  /**
   * @return \Symfony\Component\OptionsResolver\OptionsResolver
   * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
   * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
   */
  public static function getConfigurableOptions(): OptionsResolver
  {
    $resolver = new OptionsResolver();
    $resolver->setDefaults([
        'triggered_by' => ['twig.html', 'twig'],
        'severity' => '',
        'whitelist_patterns' => [],
    ]);
    $resolver->addAllowedTypes('triggered_by', ['array']);
    $resolver->addAllowedTypes('whitelist_patterns', ['array']);
    $resolver->addAllowedTypes('severity', ['string']);
    return $resolver;
  }

  /**
   * @param ContextInterface $context
   *
   * @return bool
   */
  public function canRunInContext(ContextInterface $context): bool
  {
    return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
  }

  /**
   * @param ContextInterface $context
   *
   * @return \GrumPHP\Runner\TaskResult
   * @throws \GrumPHP\Exception\RuntimeException
   * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
   * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
   * @throws \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
   * @throws \Symfony\Component\OptionsResolver\Exception\NoSuchOptionException
   * @throws \Symfony\Component\OptionsResolver\Exception\OptionDefinitionException
   * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
   */
  public function run(ContextInterface $context): TaskResultInterface
  {
    $config = $this->getConfig()->getOptions();
    $files = $context->getFiles()->extensions($config['triggered_by']);
    $whitelistPatterns = $config['whitelist_patterns'];
    $extensions = '/\.('.implode('|', $config['triggered_by']).')$/i';

    $files = $context->getFiles()->name($extensions);
    if (\count($whitelistPatterns)) {
        $files = $context->getFiles()->paths($whitelistPatterns)->name($extensions);
    }

    if (0 === count($files)) {
      return TaskResult::createSkipped($this, $context);
    }

    $linterLogs = [];

    /** @var \Symfony\Component\Finder\SplFileInfo $file */
    foreach ($files as $file) {
      $arguments = $this->processBuilder->createArgumentsForCommand('twigcs');
      $arguments->add($file->getRealPath());
      $process = $this->processBuilder->buildProcess($arguments);
      $process->run();
      if (!$process->isSuccessful()) {
        $linterLogs[] = $this->formatter->format($process);
      }
    }
    if (count($linterLogs) > 0) {
      return TaskResult::createFailed($this, $context, implode("\n", $linterLogs));
    }
    return TaskResult::createPassed($this, $context);
  }

}

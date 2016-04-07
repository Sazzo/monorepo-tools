<?php

namespace SS6\ShopBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class MailerSettingExtension extends Twig_Extension {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var bool
	 */
	private $isDeliveryDisabled;

	/**
	 * @var string
	 */
	private $mailerMasterEmailAddress;

	/**
	 * @var string[]
	 */
	private $mailerWhitelistExpessions;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->isDeliveryDisabled = $this->container->getParameter('mailer_disable_delivery');
		$this->mailerMasterEmailAddress = $this->container->getParameter('mailer_master_email_address');
		$this->mailerWhitelistExpessions = $this->container->getParameter('mailer_delivery_whitelist');
	}

	/**
	 * Get service "templating" cannot by called in constructor - https://github.com/symfony/symfony/issues/2347
	 * beacause it causes circular dependency
	 *
	 * @return \Twig_Environment
	 */
	private function getTemplatingService() {
		return $this->container->get('templating');
	}

	/**
	 * @return array
	 */
	public function getFunctions() {
		return [
			new Twig_SimpleFunction('isMailerSettingUnusual', [$this, 'isMailerSettingUnusual']),
			new Twig_SimpleFunction('getMailerSettingInfo', [$this, 'getMailerSettingInfo'], ['is_safe' => ['html']]),
		];
	}

	/**
	 * @return bool
	 */
	public function isMailerSettingUnusual() {
		return $this->isDeliveryDisabled || (!$this->isDeliveryDisabled && $this->mailerMasterEmailAddress !== null);
	}

	/**
	 * @return string
	 */
	public function getMailerSettingInfo() {
		return $this->getTemplatingService()->render('@SS6Shop/Common/Mailer/settingInfo.html.twig', [
			'isDeliveryDisabled' => $this->isDeliveryDisabled,
			'mailerMasterEmailAddress' => $this->mailerMasterEmailAddress,
			'mailerWhitelistExpessions' => $this->mailerWhitelistExpessions,
		]);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'ss6.twig.mailer_setting_extension';
	}

}
<?php
namespace Dfe\Stat\Plugin\Framework\View\Asset\MergeStrategy;
use Magento\Framework\App\ObjectManager as OM;
use Magento\Framework\View\Asset\File as F;
use Magento\Framework\View\Asset\LocalInterface as IL;
use Magento\Framework\View\Asset\MergeStrategy\Checksum as Sb;
use Magento\Framework\View\Asset\Source;
// 2018-10-26
final class Checksum {
	/**
	 * 2018-10-26
	 * It fixes the «Cannot gather stats» error on clearing the static files cache via the Magento 2 backend.
	 * @see \Magento\Framework\View\Asset\MergeStrategy\Checksum::merge()
	 * @param Sb $sb
	 * @param array(string => F) $a
	 * @param IL|F $il
	 * @return mixed[]
	 */
	function beforeMerge(Sb $sb, array $a, IL $il) {
		$om = OM::getInstance(); /** @var OM $om */
		$s = $om->get(Source::class); /** @var Source $s */
		return [
			!$il instanceof F || 'js' !== $il->getContentType() ? $a :
				array_filter($a, function(F $f) use($s) {return file_exists($s->findSource($f));})
			, $il
		];
	}
}
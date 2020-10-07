<?php
/**
 * Unit tests covering Links_Processor functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Frontend
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Frontend;

/**
 * Test frontend/class-links-processor.php
 *
 * @coversDefaultClass \Remove_Noreferrer\Frontend\Links_Processor
 * @group frontend
 */
class Links_Processor_Test extends \PHPUnit\Framework\TestCase {
	/**
	 * Remove_Noreferrer\Frontend\Links_Processor instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Links_Processor $_processor
	 */
	private $_processor;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();

		$this->_processor = new Links_Processor();
	}

	/**
	 * @dataProvider data_call
	 *
	 * @covers ::call
	 * @covers ::is_links_found
	 * @covers ::is_noreferrer_found
	 * @covers ::remove_noreferrer
	 * @covers ::remove_extra_spaces
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $input The input string.
	 * @param string $expected The expected function output.
	 * @param string $attribute_to_remove The attribute to remove.
	 *
	 * @return void
	 */
	public function test_call( $input, $expected, $attribute_to_remove ) {
		$result = $this->_processor->call( $input, $attribute_to_remove );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function data_call() {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'no links found' => array(
				'test',
				'test',
				'',
			),
			'no noreferrer found' => array(
				'<a rel="nofollow">test</a>',
				'<a rel="nofollow">test</a>',
				'',
			),
			'noreferrer found' => array(
				'<a rel="nofollow noreferrer">test</a>',
				'<a rel="nofollow">test</a>',
				'',
			),
			'rel values inside single quotes' => array(
				'<a class="test"   rel=\'nofollow noreferrer\' name="test">test</a>',
				'<a class="test"   rel=\'nofollow\' name="test">test</a>',
				'',
			),
			'link has attributes before rel' => array(
				'<a class="test"   rel="nofollow noreferrer">test</a>',
				'<a class="test"   rel="nofollow">test</a>',
				'',
			),
			'link has attributes after rel' => array(
				'<a rel="nofollow noreferrer" name="test">test</a>',
				'<a rel="nofollow" name="test">test</a>',
				'',
			),
			'removes extra spaces in rel' => array(
				'<a rel="  nofollow   noreferrer   ugc ">test</a>',
				'<a rel="nofollow ugc">test</a>',
				'',
			),
			'two links' => array(
				'<a rel="nofollow noreferrer">test</a> <a rel="ugc noreferrer nofollow">test2</a>',
				'<a rel="nofollow">test</a> <a rel="ugc nofollow">test2</a>',
				'',
			),
			'rel="nofollow" exists outside the link' => array(
				'<a rel="nofollow noreferrer">rel="noreferrer"</a>',
				'<a rel="nofollow">rel="noreferrer"</a>',
				'',
			),
			'no target blank found' => array(
				'<a rel="nofollow">test</a>',
				'<a rel="nofollow">test</a>',
				'target',
			),
			'target blank found' => array(
				'<a target="_blank">test</a>',
				'<a target="">test</a>',
				'target',
			),
			'target values inside single quotes' => array(
				'<a class="test"   target=\'_blank\' name="test">test</a>',
				'<a class="test"   target=\'\' name="test">test</a>',
				'target',
			),
			'link has attributes before target' => array(
				'<a class="test"   target="_blank">test</a>',
				'<a class="test"   target="">test</a>',
				'target',
			),
			'link has attributes after target' => array(
				'<a target="_blank" name="test">test</a>',
				'<a target="" name="test">test</a>',
				'target',
			),
			'removes extra spaces in target' => array(
				'<a target=" _blank  ">test</a>',
				'<a target="">test</a>',
				'target',
			),
			'target two links' => array(
				'<a target="_blank">test</a> <a target="_blank">test2</a>',
				'<a target="">test</a> <a target="">test2</a>',
				'target',
			),
			'target="_blank" exists outside the link' => array(
				'<a target="_blank">target="_blank"</a>',
				'<a target="">target="_blank"</a>',
				'target',
			),

		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}
}

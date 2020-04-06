<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Frontend\Tests;

use PHPUnit\Framework\TestCase;
use Remove_Noreferrer\Frontend\Links_Processor;

class Links_Processor_Test extends TestCase {
	/**
	 * Remove_Noreferrer\Frontend\Links_Processor instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Links_Processor $_processor
	 */
	private $_processor;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	protected function setUp(): void {
		$this->_processor = new Links_Processor();
	}

	/**
	 * Test the Links_Processor::call() method.
	 *
	 * @dataProvider data_call
	 *
	 * @covers \Remove_Noreferrer\Frontend\Links_Processor::call
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param string $input The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_call( $input, $expected ) {
		$result = $this->_processor->call( $input );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider
	 *
	 * @since 1.3.0
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
			),
			'no noreferrer found' => array(
				'<a rel="nofollow">test</a>',
				'<a rel="nofollow">test</a>',
			),
			'noreferrer found' => array(
				'<a rel="nofollow noreferrer">test</a>',
				'<a rel="nofollow">test</a>',
			),
			'rel values inside single quotes' => array(
				'<a class="test"   rel=\'nofollow noreferrer\' name="test">test</a>',
				'<a class="test"   rel=\'nofollow\' name="test">test</a>',
			),
			'link has attributes before rel' => array(
				'<a class="test"   rel="nofollow noreferrer">test</a>',
				'<a class="test"   rel="nofollow">test</a>',
			),
			'link has attributes after rel' => array(
				'<a rel="nofollow noreferrer" name="test">test</a>',
				'<a rel="nofollow" name="test">test</a>',
			),
			'removes extra spaces in rel' => array(
				'<a rel="  nofollow   noreferrer   ugc ">test</a>',
				'<a rel="nofollow ugc">test</a>',
			),
			'two links' => array(
				'<a rel="nofollow noreferrer">test</a> <a rel="ugc noreferrer nofollow">test2</a>',
				'<a rel="nofollow">test</a> <a rel="ugc nofollow">test2</a>',
			),
			'rel="nofollow" exists outside the link' => array(
				'<a rel="nofollow noreferrer">rel="noreferrer"</a>',
				'<a rel="nofollow">rel="noreferrer"</a>',
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}
}
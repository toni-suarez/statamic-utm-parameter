<?php

namespace Suarez\StatamicUtmParameters\Tests;

use Suarez\StatamicUtmParameters\UtmParameter;
use Suarez\StatamicUtmParameters\Tests\TestCase;

class UtmParametersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $parameters = [
            'utm_source'   => 'google',
            'utm_medium'   => 'cpc',
            'utm_campaign' => '{campaignid}',
            'utm_content'  => '{adgroupid}',
            'utm_term'     => '{targetid}',
        ];

        app()->singleton(UtmParameter::class, fn() => new UtmParameter());
        app(UtmParameter::class)->boot($parameters);
    }

    public function test_it_should_be_bound_in_the_app()
    {
        $utm = app(UtmParameter::class);
        $this->assertInstanceOf(UtmParameter::class, $utm);
    }

    public function test_it_should_have_an_utm_attribute_bag()
    {
        $utm = UtmParameter::all();
        $this->assertIsArray($utm);
        $this->assertNotEmpty($utm);
        $this->assertArrayHasKey('utm_source', $utm);
    }

    public function test_it_should_have_a_source_parameter()
    {
        $source = UtmParameter::get('source');
        $this->assertNotEmpty($source);
        $this->assertIsString($source);
        $this->assertEquals('google', $source);
    }

    public function test_it_should_have_work_with_utm_inside_key()
    {
        $source = UtmParameter::get('utm_source');
        $this->assertNotEmpty($source);
        $this->assertIsString($source);
        $this->assertEquals('google', $source);
    }

    public function test_it_should_have_a_medium_parameter()
    {
        $medium = UtmParameter::get('medium');
        $this->assertNotEmpty($medium);
        $this->assertIsString($medium);
        $this->assertEquals('cpc', $medium);
    }

    public function test_it_should_have_a_campaign_parameter()
    {
        $campaign = UtmParameter::get('campaign');
        $this->assertNotEmpty($campaign);
        $this->assertIsString($campaign);
        $this->assertEquals('{campaignid}', $campaign);
    }

    public function test_it_should_have_a_content_parameter()
    {
        $content = UtmParameter::get('content');
        $this->assertNotEmpty($content);
        $this->assertIsString($content);
        $this->assertEquals('{adgroupid}', $content);
    }

    public function test_it_should_have_a_term_parameter()
    {
        $term = UtmParameter::get('term');
        $this->assertNotEmpty($term);
        $this->assertIsString($term);
        $this->assertEquals('{targetid}', $term);
    }

    public function test_it_should_handle_invalid_key_via_get()
    {
        $invalidParameter = UtmParameter::get('invalid_key');
        $this->assertNull($invalidParameter);
    }

    public function test_it_should_handle_invalid_key_via_has()
    {
        $invalidParameter = UtmParameter::has('invalid_key');
        $this->assertFalse($invalidParameter);
    }

    public function test_it_should_determine_if_utm_has_key()
    {
        $hasSource = UtmParameter::has('source');
        $this->assertIsBool($hasSource);
        $this->assertTrue($hasSource);
    }

    public function test_it_should_determine_if_utm_has_not_key()
    {
        $hasRandomKey = UtmParameter::has('random-key');
        $this->assertIsBool($hasRandomKey);
        $this->assertFalse($hasRandomKey);
    }

    public function test_it_should_determine_if_utm_has_key_and_value()
    {
        $hasGoogleSource = UtmParameter::has('utm_source', 'google');
        $this->assertIsBool($hasGoogleSource);
        $this->assertTrue($hasGoogleSource);
    }

    public function test_it_should_determine_if_utm_has_not_a_not_defined_utm_parameter()
    {
        $hasWrongParameter = UtmParameter::has('utm_something');
        $this->assertIsBool($hasWrongParameter);
        $this->assertFalse($hasWrongParameter);
    }

    public function test_it_should_determine_if_utm_has_not_key_and_value()
    {
        $hasRandomSource = UtmParameter::has('random-source', 'random-value');
        $this->assertIsBool($hasRandomSource);
        $this->assertFalse($hasRandomSource);
    }

    public function test_it_should_determine_if_an_utm_contains_a_value()
    {
        $campaign = UtmParameter::contains('utm_campaign', 'campaign');
        $this->assertIsBool($campaign);
        $this->assertTrue($campaign);
    }

    public function test_it_should_determine_if_an_utm_contains_not_a_value()
    {
        $hasRandomCampaign = UtmParameter::contains('utm_campaign', 'some-thing');
        $this->assertIsBool($hasRandomCampaign);
        $this->assertFalse($hasRandomCampaign);
    }

    public function test_it_should_determine_if_an_utm_contains_a_non_string_value()
    {
        $campaign = UtmParameter::contains('utm_campaign', null);
        $this->assertIsBool($campaign);
        $this->assertFalse($campaign);

        $term = UtmParameter::contains('utm_term', false);
        $this->assertIsBool($term);
        $this->assertFalse($term);

        $content = UtmParameter::contains('utm_content', []);
        $this->assertIsBool($content);
        $this->assertFalse($content);

        $medium = UtmParameter::contains('utm_medium', 1);
        $this->assertIsBool($medium);
        $this->assertFalse($medium);
    }

    public function test_it_should_clear_and_remove_the_utm_parameter_again()
    {
        $source = UtmParameter::get('source');
        $this->assertEquals('google', $source);

        UtmParameter::clear();
        $emptySource = UtmParameter::get('source');
        $this->assertNull($emptySource);
    }
}

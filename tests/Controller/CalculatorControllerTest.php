<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatorControllerTest extends WebTestCase
{
    public function testCalculatorPageIsDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/calculator');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'TechPods interview task');

        $buttons = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '+', '*', '/'];

        foreach ($buttons as $button) {
            self::assertSelectorTextContains('button[data-value="' . $button . '"]', $button);
        }

        self::assertSelectorExists('input#calculator-expression');
        self::assertSelectorExists('button#calculate-result');
    }

    public function testCalculationSuccessResponse(): void
    {
        $client = static::createClient();
        $client->request('POST', '/calculator/evaluate', ['expression' => '5+6*8/3']);
        self::assertResponseIsSuccessful();

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('21', $responseData['result']);
    }

    public function testCalculationErrorResponse(): void
    {
        $client = static::createClient();
        $client->request('POST', '/calculator/evaluate', ['expression' => '5+6*8/0']);
        self::assertResponseStatusCodeSame(400);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());

        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals(
            'Division by zero is not allowed in this universe. Try in another.',
            $responseData['error']
        );
    }

    public function testCalculationErrorResponseEmptyExpression(): void
    {
        $client = static::createClient();
        $client->request('POST', '/calculator/evaluate', ['expression' => '']);
        self::assertResponseStatusCodeSame(400);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());

        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals(
            'Invalid or missing math expression',
            $responseData['error']
        );
    }
}

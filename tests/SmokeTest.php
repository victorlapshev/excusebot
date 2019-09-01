<?php

class SmokeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMain()
    {
        $this->get('/');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

//    public function testWebhook()
//    {
//        $this->json('post', '/webhook', [
//            'update_id' => 846954180,
//            'inline_query' =>
//                [
//                    'id' => '541750146901848262',
//                    'from' =>
//                        [
//                            'id' => 126136035,
//                            'is_bot' => false,
//                            'first_name' => 'Виктор',
//                            'username' => 'eW91IGFyZSBmYWdnb3Q',
//                            'language_code' => 'ru',
//                        ],
//                    'query' => '',
//                    'offset' => '',
//                ],
//        ]);
//        $this->assertEquals(200, $this->response->getStatusCode());
//    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class apiGatewayController extends Controller
{
    public function sendMessage(array $data)
    {
        $postData = [
            "sender" => "6281113801734",
            "queue" => "11074-171462159323730",
            "timeout" => 86400,
            "is_group" => false,
            "destination" => $data['destination'],
            "message" => $data['message'],
        ];
        // echo json_encode($postData); exit();
        $client = new Client();

        try {
            $url = 'https://api.wachat-api.com/wachat_api/1.0/message';
            $response = $client->post($url, [
                'headers' => [
                    'APIKey' => '244E15501D20492BB35C209DDEC4ECFE',
                ],
                'json' => $postData,
            ]);

            $responseData = $response->getBody()->getContents();
            $responseBody = json_decode($responseData, true);

            // dd($response);
            return $responseBody;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            }
            return $e->getMessage();
        }
    }
    public function sendMessageFromRequest(Request $request)
    {
        // echo json_encode('adsas');exit;
        $request->validate([
            'destination' => 'required|string|regex:/^[0-9]{10,15}$/',
            'message' => 'required|string',
        ]);

        //    dd( $request->all());
        $data = $request->only(['destination', 'message']);

        $response = $this->sendMessage($data);
        // dd($request->all());

        if (is_array($response)) {
            return redirect()->back()->with('status', 'Message sent successfully!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to send message: ' . $response]);
        }
    }
}

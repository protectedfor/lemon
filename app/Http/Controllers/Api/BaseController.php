<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Log;
use pdeans\Builders\XmlBuilder;
use SimpleXMLElement;

class BaseController extends Controller
{


    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message)
    {
        $debugInfo = $this->getDebugInfo();

        $response = [
            'message' => $message,
            'success' => true,
            'data'    => $result,
        ];

        if (env('APP_DEBUG'))
            $response['debug'] = $debugInfo;

        return response()->json($response, 200);
    }

    private function getDebugInfo()
    {
        $queryLogs = DB::getQueryLog();

        $queries = [];
        $timeExpenses = 0;

        foreach ($queryLogs as $log) {
            $timeExpenses += $log['time'];
            $queries[] = [
                'query' => vsprintf(str_replace('?', '`%s`', $log['query']), $log['bindings']),
                'time'  => $log['time'],
            ];
        }

        return [
            'queries_count'        => count($queries),
            'total_execution_time' => round($timeExpenses, 2),
            'queries'              => $queries,
        ];

    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $debugInfo = $this->getDebugInfo();

        $response = [
            'message' => $error,
            'success' => false,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        if (env('APP_DEBUG'))
            $response['debug'] = $debugInfo;

        return response()->json($response, $code);
    }

    /**
     * @param $phone
     * @param string $text
     * @return bool|SimpleXMLElement
     * @throws GuzzleException
     */
    protected function sendNikitaSMS($phone, $text = '')
    {
        $builder = new XmlBuilder;
        $xml = $builder->create('message', [
            '@t' => [
                'login'  => env('SMSPRO_NIKITA_LOGIN'),
                'pwd'    => env('SMSPRO_NIKITA_PASS'),
                'id'     => confirmation_code(6),
                'sender' => env('SMSPRO_NIKITA_SENDER'),
                'text'   => $text,
                'time'   => "",
                'phones' => [
                    'phone' => $phone
                ],
                'test'   => env('SMSPRO_NIKITA_ENABLED') ? 0 : 1,
            ],
        ]);

        try {

            $httpClient = new Client();
            $response = $httpClient->request('POST', "http://smspro.nikita.kg/api/message", [
                'body' => $xml,
            ]);
            $data = $response->getBody();
            $xml = new SimpleXMLElement($data);

            if ($xml->status !== 11 || $xml->status !== 0) {
                Log::warning('Errors sending sms to ' . $phone . '. Response status: ' . $xml->status);
            }

            return $xml->status;

        } catch (Exception $e) {
            Log::warning('Errors sending sms to ' . $phone . '. Message: ' . $e->getMessage() . '; Code: ' . $e->getCode());
        }

        return false;
    }
}

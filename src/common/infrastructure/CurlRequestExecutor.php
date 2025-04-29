<?php

namespace luismacayo\RacFormater\common\infrastructure;

class CurlRequestExecutor implements RequestExecutorInterface
{
    public function execute(RequestInterface $request)
    {
        $curl = curl_init();

        $this->configureCurlOptions($curl, $request);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            throw new \RuntimeException("CURL error: $error");
        }

        return $response;
    }

    public function executeAll(array $requests): array
    {
        $multiHandle = curl_multi_init();
        $curlHandles = [];
        $results = [];

        // Initialize all CURL handles and add them to the multi-handle
        foreach ($requests as $providerName => $request) {
            $curl = curl_init();
            $this->configureCurlOptions($curl, $request);
            curl_multi_add_handle($multiHandle, $curl);
            $curlHandles[$providerName] = $curl;
        }

        // Execute all requests simultaneously
        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        // Get all responses and clean up
        foreach ($curlHandles as $providerName => $curl) {
            $results[$providerName] = curl_multi_getcontent($curl);
            curl_multi_remove_handle($multiHandle, $curl);
        }

        curl_multi_close($multiHandle);

        return $results;
    }

    private function configureCurlOptions($curl, RequestInterface $request): void
    {
        curl_setopt($curl, CURLOPT_URL, $request->getUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($request->getMethod() === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            if ($request->getBody()) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
            }
        }

        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = "$name: $value";
        }

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        foreach ($request->getOptions() as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
    }
}
<?php

namespace AttendCheck\Api;

class QueryStringBuilder
{
    public function build(array $data, $privateKey)
    {
        $data['Key'] = env('TQF_PUBLICKEY');
    
        $dataString = $this->buildDataString($data);

        return ['q' => $this->encrypt($dataString, $privateKey)];
    }

    public function encrypt($string, $privateKey)
    {
        $result = '';

        for ($i=0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($privateKey, ($i % strlen($privateKey)) - 1, 1);
            $char = chr(ord($char)+ord($keychar));

            $result .= $char;
        }

        return base64_encode($result);
    }

    public function buildDataString(array $data)
    {
        $result = '';

        foreach ($data as $key => $value) {
            $newKeyName = $this->getKeyName($key);
            $newKeyName = $newKeyName ? $newKeyName : $key;

            if ($key != 'Key') {
                $result .= "g_$newKeyName=$value,";
            } else {
                $result .= "Key=$value";
            }
        }

        return $result;
    }

    private function getKeyName($key)
    {
        switch ($key) {
            case 'course':
                return 'ccode';

            case 'year':
                return 'qyear';

            default:
                return null;
        }
    }
}

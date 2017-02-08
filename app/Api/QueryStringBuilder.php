<?php

namespace AttendCheck\Api;

class QueryStringBuilder
{
    /**
     * Build an encrypted query string builder.
     * 
     * @param  array  $data
     * @param  string $privateKey
     * @return array  an array contains query string for use with Guzzle library.
     */
    public function build(array $data, $privateKey)
    {
        // Append public key at the end of data array.
        $data['Key'] = env('TQF_PUBLICKEY');
    
        $dataString = $this->buildDataString($data);

        return ['q' => $this->encrypt($dataString, $privateKey)];
    }

    /**
     * Encrypt string using private key.
     *
     * The point of this method is that we don't want the user to see
     * the query string in plain text, so we will encrypt it using
     * our private keys and then decrypt it in server's side
     * using the reverse version of this very algorithm.
     * 
     * @param  string $string
     * @param  string $privateKey
     * @return string
     */
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

    /**
     * Build data string before encrypt.
     *
     * This method build the data string in the format that API server requires.
     * 
     * @param  array  $data
     * @return string
     */
    public function buildDataString(array $data)
    {
        $result = '';

        foreach ($data as $key => $value) {

            // Look to see if key has any server's corresponding key name.
            // If it does, use it. If it doesn't, use the original key.
            $newKeyName = $this->getKeyName($key);
            $newKeyName = $newKeyName ? $newKeyName : $key;

            if ($key != 'Key') {
                $result .= "g_$newKeyName=$value,";
            } else {
                // We want the public key to be the last item in our query string,
                // so when we reach the 'Key', we will stop adding taling comma.
                $result .= "Key=$value";
            }
        }

        return $result;
    }

    /**
     * Get server's corresponding key name.
     * 
     * @param  string $key
     * @return string
     */
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

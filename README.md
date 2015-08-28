PHP jsonable
========

PHPjsonable is small php library to decode simple bean objects from and to Json format (Actually it's conversion of mine Java jsonable library with light changes to make it suitable with PHP)

Since it has come from Java, you will find java "terms" like InputStream and OutputStream.
These objects simply wrappers for stream/string in PHP.

Let's start with examples. 

First example is about array. Actually, if you need JSON serializer only for built-in types in PHP (array, int, string, bool) - you prefer to use built-in [json_encode](http://php.net/manual/en/function.json-encode.php) and [json_decode](http://php.net/manual/en/function.json-decode.php)
In such case you don't need my library :)
So, let's start: Assume you have php array:

    $ar = ["hello", "bye", "something else"];
    
and you need to send it to some remote server using JSON format.
So, let's assume you need it outputted into string (in order to send using curl, guzzle or other http tool)

    $output = new StringOutputStream(); // creating output wrapper for string
    Json::encode($ar, $output);
    echo $output->toString();
    // output will be: "["hello", "bye", "something else"]" 

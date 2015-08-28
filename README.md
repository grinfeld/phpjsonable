PHP jsonable
========

PHPjsonable is small php library to decode simple bean objects from and to Json format (Actually it's conversion of mine Java jsonable library with light changes to make it suitable with PHP)

Since it has come from Java, you will find java "terms" like **InputStream** and **OutputStream**.
These objects simply wrappers for stream/string in PHP.

Actually, if you need JSON serializer only for built-in types in PHP (array, int, string, bool) - you prefer to use built-in [json_encode](http://php.net/manual/en/function.json-encode.php) and [json_decode](http://php.net/manual/en/function.json-decode.php)
In such case you don't need my library :)

So, let's start with few simple examples: Assume you have php array:

    $ar = ["hello", "bye", "something else"];
    
and you need to send it to some remote server using JSON format.

So, let's assume you need it outputted into string (in order to send using curl, guzzle or other http tool)

    $output = new StringOutputStream(); // creating output wrapper for string
    Json::encode($ar, $output);
    echo $output->toString();
    // output will be: "["hello", "bye", "something else"]" 

Let's see same example, but when we need to store data in a file

    $fp = fopen("some.json", "r+")
    $output = new StreamOutputStream($fp); // creating output wrapper for string
    Json::encode($ar, $output);
    fclose($fp);
    echo file_get_contents("some.json");
    // file content is the same as in previous example: "["hello", "bye", "something else"]" 
    
Actually **StreamOutputStream** wraps any PHP streams which has *fputs* function: "php:file://" , "php://memory", "php://temp" and etc.
If you need your own stream, create class which implements **OutputStream** and has method *write($str)*. 


Let's see example when you receive response from some remote server:

    $content = "..." // let's assume you received: "{\"status":"OK"}"
    $input = new StringInputStream($content);
    $myResult = Json::decode($input);
    echo $myResult["status"];
    // output: OK
    
After few basics, now we can move to more complicated examples. As I've already said, this package is for serializing objects and not simple PHP structures.
Let's see few examples:

Assume, you have **Foo** class:

    class Foo {
        protected $str;
        protected $num;
        protected $ar;
        
        public function __construct() {
            $this->str = "Hello";
            $this->num = 100;
            $this->ar = array(1,2,3,4);
        }
    }
    
Now, we send this class to some remote server:
 
    $output = new StringOutputStream(); // creating output wrapper for string
    Json::encode($ar, $output);
    // $output->toString() will return: "{"str":"Hello","num":100,"ar":[1,2,3,4]}" 
    // sending data (by calling $output->toString()) to server (use your prefer http tool) 
    ......
    ......
    ......
    $response = "...."; // getting response "{\"status":100, "description":"OK"}"
    $myResult = Json::decode(new StringInputStream($response));
    echo $myResult["status"] . " --- " . $myResult["description"]; // output    

As we can see, our remote server receives nice JSON string, does anything it should and returns response we can read. 
If you sure that response is always returned as assoc array, sequence array or any PHP built-in type, you can replace
*$myResult = Json::decode(new StringInputStream($response));* to *$myResult = json_decode($response)*
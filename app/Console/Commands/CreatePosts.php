<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpDocumentor\Reflection\Types\Integer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

/**
 * Class CreatePosts
 * @package App\Console\Commands
 */
class CreatePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var $authors array
     */
    protected $authors;

    /**
     * @var $listIp array
     */
    protected $listIp;

    /**
     * @var $post array
     */
    protected $post;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->build();
        $this->savePosts();
    }

    /**
     * @return bool
     */
    protected function createAuthors()
    {
        for ($i = 0; $i < 100; $i++) {
            $login = $this->generateString(5);
            $this->authors[] = $login;
        }

        return true;
    }

    /**
     * @param int $length
     * @return string
     */
    protected function generateString(int $length): string
    {
        $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        $charsLength = strlen($characters) -1;
        $string = "";

        for($i = 0; $i < $length; $i++) {
            $randNum = mt_rand(0, $charsLength);
            $string .= $characters[$randNum];
        }

        return $string;
    }

    /**
     * @return bool
     */
    protected function generateIp()
    {
        for ($i = 0; $i < 50; $i++) {
            $this->listIp[] = long2ip(mt_rand());
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function createPostData()
    {
        for ($i = 0; $i < 200000; $i++) {

            $this->post[] = [
                'login' => $this->authors[rand(0, 99)],
                'title' => $this->generateString(20),
                'content' => $this->generateString(250),
                'author_ip' => $this->listIp[rand(0, 49)]
            ];
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function build()
    {
        $this->createAuthors();
        $this->generateIp();
        $this->createPostData();

        return true;
    }

    /**
     * @param $post
     */
    protected function sendPost($post)
    {
        $client = new Client();

        $response = $client->post('http://post.dev.ylab.local/api/create', [
            \GuzzleHttp\RequestOptions::JSON => $post,
            \GuzzleHttp\RequestOptions::DELAY => 750
        ]);
    }

    /**
     *
     */
    protected function savePosts()
    {
        array_map([$this, 'sendPost'], $this->post);
    }
}

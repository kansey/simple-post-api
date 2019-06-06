<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
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
     *  Count rows for table posts
     */
    const POST_COUNT = 200000;

    const IP_COUNT = 50;

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
     * @var $users
     */
    protected $users;

    /**
     * @var $listIp array
     */
    protected $listIp;

    /**
     * @var $post array
     */
    protected $post;

    /**
     * @var Client $guzzle
     */
    protected $guzzle;

    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * CreatePosts constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->guzzle = new Client([]);
        $this->userRepository = $userRepository;
        $this->users = $this->userRepository->find([]);
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
        for ($i = 0; $i < CreatePosts::IP_COUNT; $i++) {
            $this->listIp[] = long2ip(mt_rand());
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function createPostData()
    {
        for ($i = 0; $i < CreatePosts::POST_COUNT; $i++) {

            $this->post[] = [
                'login' => $this->users->random()->login,
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
        $this->generateIp();
        $this->createPostData();

        return true;
    }

    /**
     * @param $post
     */
    protected function sendPost($post, $index)
    {
        $response = $this->guzzle->post('http://post.dev.ylab.local/api/create', [
            \GuzzleHttp\RequestOptions::JSON => $post,
            \GuzzleHttp\RequestOptions::DELAY => 1000
        ]);

        echo $index.PHP_EOL;
    }

    /**
     * @return bool
     */
    protected function savePosts()
    {
        return array_walk($this->post, [$this, 'sendPost']);
    }
}

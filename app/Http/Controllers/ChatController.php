<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ChatController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function landing(Request $request)
    {
        return view("landing", ["glpi" => env("GLPI_AUTH")]);
    }

    public function login(Request $request)
    {

        if (env("GLPI_AUTH")) {
            //Okay so before everything, on the GLPI instance I'm working, the API is disabled and this is the only way. I swear!
            $getPage = Http::get('https://glpi.limayrac.fr/front/login.php');
            $obj = (string)$getPage;

            $domdoc = new DOMDocument();
            $domdoc->loadHTML($obj);
            $xpath = new DOMXpath($domdoc);

            $query = "//input[@name]";
            $entries = $xpath->query($query);

            $tokens = [];
            foreach ($entries as $p) {
                if ($p->getAttribute('name') === "_glpi_csrf_token") {
                    $tokens['_glpi_csrf_token'] = $p->getAttribute('value');
                } else if ($p->getAttribute('name') === "submit") {
                    $tokens['submit'] = $p->getAttribute('value');
                } else {
                    $tokens[$p->getAttribute('id')] = $p->getAttribute('name');
                }
            }

            //I spent so much time on this, please don't break one day
            $loginGLPI = Http::asMultipart()
                ->withoutRedirecting()
                ->withHeaders([
                    //The ->withCookies don't work as it should so instead I do it like this and it works (surprisingly)
                    "Cookie" => $getPage->cookies()->toArray()[0]['Name'] . "=" . $getPage->cookies()->toArray()[0]["Value"]
                ])
                //It's just for "aesthetics", you can do the request with "guzzlehttp/request" it works
                ->withUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36")
                ->post('https://glpi.limayrac.fr/front/login.php', [
                    $tokens['login_name'] => $request->glpi_username,
                    $tokens['login_password'] => $request->glpi_password,
                    $tokens['login_remember'] => "on",
                    "submit" => $tokens['submit'],
                    "_glpi_csrf_token" => $tokens['_glpi_csrf_token']
                    //there is actually a 6th option, "noAUTO" (set to 1), i still don't know what for... Btw this option doesn't show up all the time
                ]);

//            dump($loginGLPI->redirect(), $loginGLPI->cookies()->toArray());

            if (!($loginGLPI->cookies()->toArray()[1]['Name'] === $loginGLPI->cookies()->toArray()[0]['Name'] . "_rememberme")) {
                //We don't have the rememberme cookie then connection failed
                session("error", "Erreur de connexion GLPI (login/password invalide)");
                return redirect("landing");
            }

            //Now, we know we have a session token, we can retrive the email
            $emailGLPI = Http::withHeaders([
                "Cookie" => $loginGLPI->cookies()->toArray()[0]['Name'] . "=" . $loginGLPI->cookies()->toArray()[0]["Value"]
            ])
                ->get("https://glpi.limayrac.fr/ajax/common.tabs.php", [
                    //Don't ask my why only those 2 when the og request have 5 but it works, without it doesn't so idc now
                    "_itemtype" => "Preference",
                    "_glpi_tab" => "User$1"
                ]);

            $i = 0;
            $values = [];
            //Weirdly I can't transform that to a DOMDocument
            foreach (explode("\n", $emailGLPI->body()) as $item) {
                if (str_contains($item, "@limayrac.fr")) {
                    $values = (explode("'", $item));
                    for ($i = 0; $i < count($values); $i++) {
                        if ($values[$i] === " value=") break;
                    }
                    break;
                }
            }

            $email = $values[$i + 1];
            $student = explode(".", substr($email, 0, -12));

            $user = new User([
                "firstname" => $student[0],
                "lastname" => $student[1],
                "glpi_user" => $request->glpi_username,
                "username" => ucfirst(strtolower($student[0])) . " " . ucfirst(substr($student[1], 0, 1)),
                "ip" => $_SERVER['REMOTE_ADDR']
            ]);

        } else {
            if(!isset($request->username)) return redirect("landing");
            $user = new User([
                "username" => $request->username,
                "ip" => $_SERVER['REMOTE_ADDR']
            ]);
        }
//        $user->ip = $_SERVER['REMOTE_ADDR'];
        $user->save();
        $_SESSION['id'] = json_encode($user);
        return redirect("chat");
    }

    public function chat(Request $request)
    {
//        $messages = DB::select('SELECT * FROM messages ORDER BY id LIMIT 0,20');
        return view("chat");
    }

//    public function keepAlive(){
//        DB::update('UPDATE users SET last_alive = '.time().' WHERE ip = "'.$_SERVER['REMOTE_ADDR'].'"');
//        return json_encode(["code" => "201"]);
//    }
}

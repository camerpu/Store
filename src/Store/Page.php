<?php

namespace Store;

use MyServices\addLVL;
use MyServices\addZN;
use MyServices\makeTransfer;
use MyServices\remSilence;
use MyServices\vipForum;
use MyServices\vipGame;
use \PDO;

class Page
{
    private $typ;

    /**
     * Page constructor.
     * @param $typ
     *
     * Coś ala routing
     */
    public function __construct(string $typ)
    {
        $this->typ = $typ;
    }

    /**
     * @param $id
     *
     * Póki co nie używane, do implementacji kiedyś
     */
    public function setID(int $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * "Routing"
     */
    public function build() : void
    {
        if (strpos($this->typ, 'vip') !== false)
        {
            $this->buildVIP();
        }
        else if (strpos($this->typ, 'index') !== false)
        {
            $this->buildIndex();
        }
        else if (strpos($this->typ, 'lvl') !== false)
        {
            $this->buildLVL();
        }
        else if (strpos($this->typ, 'transfer') !== false)
        {
            $this->buildTransfer();
        }
        else if (strpos($this->typ, 'zn') !== false)
        {
            $this->buildZN();
        }
        else if (strpos($this->typ, 'buy') !== false)
        {
            $this->buildBuy();
        }
        else if (strpos($this->typ, 'korzysci') !== false)
        {
            $this->buildKorzysci();
        }
        else if (strpos($this->typ, 'kontakt') !== false)
        {
            $this->buildKontakt();
        }
        else if (strpos($this->typ, 'inne') !== false)
        {
            $this->buildInne();
        }
        else if (strpos($this->typ, 'ub') !== false)
        {
            $this->buildUnban();
        }
        else if (strpos($this->typ, 'ForumVIP') !== false)
        {
            $this->buildForumVIP();
        }
        else if (strpos($this->typ, 'regulamin') !== false)
        {
            $this->buildRegulamin();
        }		
    }

    /**
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     *
     * Page UnSilence
     */
    private function buildUnban() : void
    {
        $dane = Service::loadService($_GET['id']);
        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);
        $typkupna =  $this->buildInformation($dane['typkupna']);

        echo $twig->render('unsilence.html.twig', ['dane' => $dane, 'typ' => $typkupna]);
        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     *
     * Page Forum VIP
     */
    private function buildForumVIP() : void
    {
        $dane = Service::loadService($_GET['id']);
        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);
        $typkupna =  $this->buildInformation($dane['typkupna']);

        echo $twig->render('vip_forum.html.twig', ['dane' => $dane, 'typ' => $typkupna]);
        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * Page ZN
     */
    private function buildZN() : void
    {
        $dane = Service::loadService($_GET['id']);
        $codmod = new Codmod();
        $ile = $codmod->getZN(Service::usePropSteamID($_SESSION['steamid32']));
        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);
        $serwery = Service::getServers();
        $typkupna =  $this->buildInformation($dane['typkupna']);

        echo $twig->render('zn.html.twig', ['dane' => $dane, 'typ' => $typkupna, 'serwery'=>$serwery, 'ile'=>$ile]);
        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * Page Transfer LVLi
     */
    private function buildTransfer() : void
    {
        $dane = Service::loadService($_GET['id']);
        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);

        $typkupna =  $this->buildInformation($dane['typkupna']);

        $codmod = new Codmod();
        $klasy = $codmod->getClasses(Service::usePropSteamID($_SESSION['steamid32']));
        $serwery = Service::getServers();

        echo $twig->render('transfer.html.twig', ['dane' => $dane, 'typ' => $typkupna, 'klasy'=>$klasy, 'serwery'=>$serwery]);

        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildKontakt() : void
    {
        $twig = TwigHelper::getTwig();
        echo $twig->render('kontakt.html.twig');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildRegulamin() : void
    {
        $twig = TwigHelper::getTwig();
        echo $twig->render('regulamin.html.twig');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildInne() : void
    {
        $twig = TwigHelper::getTwig();
        echo $twig->render('inne.html.twig');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildKorzysci() : void
    {
        $dane = Service::loadVipPrivileges();
        $twig = TwigHelper::getTwig();
        echo $twig->render('korzysci.html.twig', ['dane'=>$dane]);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildLVL() : void
    {
        $dane = Service::loadService($_GET['id']);

        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);

        $typkupna =  $this->buildInformation($dane['typkupna']);

        $codmod = new Codmod();
        $klasy = $codmod->getClasses(Service::usePropSteamID($_SESSION['steamid32']));
        $serwery = Service::getServers();

        echo $twig->render('lvl.html.twig', ['dane' => $dane, 'typ' => $typkupna, 'klasy'=>$klasy, 'serwery'=>$serwery]);

        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildVIP() : void
    {
        $dane = Service::loadService($_GET['id']);
        $twig = TwigHelper::getTwig();
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);
        $serwery = Service::getServers();
        $typkupna =  $this->buildInformation($dane['typkupna']);

        echo $twig->render('vip.html.twig', ['dane' => $dane, 'typ' => $typkupna, 'serwery'=>$serwery]);
        unset($_SESSION['error']);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildIndex() : void
    {
        $steamid = Service::usePropSteamID($_SESSION["steamid32"]);
        $services = Service::getServices($steamid);
        $twig = TwigHelper::getTwig();
        echo $twig->render('index.html.twig', ['services' => $services]);
    }

    /**
     * Zwraca informacje o tym jak dokonać płatności
     * @param $typ
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildInformation(string $typ)  : string
    {
        $twig = TwigHelper::getTwig();
        return $twig->render('type_info.html.twig', ['type' => $typ]);
    }

    /**
     * @param $parameter
     * @param $error
     * @return mixed
     *
     * Sprawdza czy istnieje dany $_POST, inaczej rzuca błędem do sesji
     */
    private function CheckPOST(string $parameter, string $error)
    {
        if(isSet($_POST[$parameter]))
        {
            return $_POST[$parameter];
        }
        else
        {
            session_start();
            $_SESSION['error'] = $error;
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }
    }

    /**
     * Obsługa kupna, przekazuje dalej informacje
     */
    private function buildBuy()
    {
        $serwer =    $this->CheckPOST('serwer', 'Brak danych dotyczących serwera');
        $code =      $this->CheckPOST('code', 'Brak podanego kodu zwrotnego');
        $serviceid = $this->CheckPOST('serviceid', 'Brak podanego ID usługi');

        try
        {
            $serwer = Service::getServerId(intval($serwer));
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }

        $dane = Service::loadService($serviceid);
        if($dane == null)
        {
            session_start();
            $_SESSION['error'] = "Błędne ID Usługi";
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }

        if($dane['codmod'] == 1)
        {
            $old = $this->CheckPOST('old', 'Brak wybranej klasy');
            $old = trim($old);
            if(strlen($old) < 3)
            {
               session_start();
               $_SESSION['error'] = "Brak odpowiedniej klasy";
                header('Location: /usluga?id=' . $_POST['serviceid']);
               exit();
            }

        }
        if($dane['codmod'] == 2)
        {
            $new = $this->CheckPOST('new', 'Brak wybranej klasy');
            $new = trim($new);
            if(strlen($new) < 3)
            {
                session_start();
                $_SESSION['error'] = "Brak odpowiedniej klasy";
                header('Location: /usluga?id=' . $_POST['serviceid']);
                exit();
            }
        }

        $service = new Service();

        $typkupna = $dane['typkupna'];
        $typkupna = $service->CheckBuyType($typkupna);

        if($typkupna != null)
        {
            $status = $service->checkSmsCode($code, $typkupna);
        }
        else
        {
            $status = $service->checkTransferCode($code);
        }

		Log::logAttempt($code, $status, $dane['nazwa'], $dane['typkupna']);
        //tutaj przestawić
        if($status == false)
        {
            session_start();
            $_SESSION['error'] = "Nieprawidłowy kod zwrotny";
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }
        else
        {
            if($dane['typ'] == 'ForumVIP')
                $this->addVipOnForum($dane, $service);
            else if($dane['typ'] == 'ub')
                $this->removeSilence($dane, $service);
            else if($dane['codmod'] == 0)
                $this->AddVIP($dane, $service, $serwer);
            else if($dane['codmod'] == 1)
                $this->AddLVL($dane, $serwer);
            else if($dane['codmod'] == 2)
                $this->MakeTransfer($dane, $serwer);
            else if($dane['codmod'] == 3)
                $this->AddZN($dane, $serwer);
            else if($dane['codmod'] == 4)
                $this->AddMEGAPREMIUM($dane, $service, $serwer);
        }
    }

    /**
     * Kontroler dodający VIPa na forum
     * @param $dane
     * @param $service
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function addVipOnForum(array $dane, Service $service) : void
    {
        $vipForum = new vipForum();
        $vipForum->tryAdd($dane);

        $nazwaserwera = Service::FormatujNazweSerwera(5);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_forumvip.html.twig');

        $data = date("Y-m-d");
        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $data, $data, $nazwaserwera);
    }

    /**
     * Zdejmowanie Silence'a
     * @param $dane
     * @param $service
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function removeSilence(array $dane, Service $service) : void
    {
        $unsilence = new remSilence();
        $unsilence->tryAdd($dane);

        $nazwaserwera = Service::FormatujNazweSerwera(5);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_unsilence.html.twig');
        $data = date("Y-m-d");
        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $data, $data, $nazwaserwera);
    }

    /**
     * Dodaje MegaPremium na Coda
     * @param $dane
     * @param $service
     * @param $serwer
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function AddMEGAPREMIUM(array $dane, Service $service, int $serwer) : void
    {
        $dateNow = date("Y-m-d");
        $currently = $dateNow;
        $dateNow = $service->detDate($dateNow, $dane['dni']);

        $megapremium = new vipGame();
        $megapremium->tryAdd($dane, $service, $serwer);

        $nazwaserwera = Service::FormatujNazweSerwera($serwer);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_megapremium.html.twig', ['nazwaserwera' => $nazwaserwera]);

        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $currently, $dateNow, $nazwaserwera);
    }

    /**
     * Dodaje ZloteNaboje na CodModa
     * @param array $dane
     * @param int $serwer
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function AddZN(array $dane, int $serwer) : void
    {
        $addZN = new addZN();
        $addZN->tryAdd($dane, null, $serwer);

        $data = date("Y-m-d");
        $nazwaserwera = Service::FormatujNazweSerwera($serwer);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_zn.html.twig', ['nazwaserwera' => $nazwaserwera, 'dni'=>$dane['dni']]);

        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $data, $data, $nazwaserwera);
    }

    /**
     * Dodaj VIPa
     * @param $dane
     * @param $service
     * @param $serwer
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function AddVIP(array $dane, Service $service, int $serwer) : void
    {
        $dateNow = date("Y-m-d");
        $currently = $dateNow;
        $dateNow = $service->detDate($dateNow, $dane['dni']);

        $vipGame = new vipGame();
        $vipGame->tryAdd($dane, $service, $serwer);

        $nazwaserwera = Service::FormatujNazweSerwera($serwer);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_vip.html.twig', ['nazwaserwera' => $nazwaserwera]);

        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $currently, $dateNow, $nazwaserwera);
    }

    /**
     * Dodaj LVL Na CodMod
     * @param array $dane
     * @param int $serwer
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function AddLVL(array $dane, int $serwer) : void
    {
        $data = date("Y-m-d");

        $addLVL = new addLVL();
        $addLVL->tryAdd($dane, null, $serwer);
        $nazwaserwera = Service::FormatujNazweSerwera($serwer);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_lvl.html.twig', ['nazwaserwera' => $nazwaserwera, 'ilelvli'=>$dane['dni']]);

        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $data, $data, $nazwaserwera);
    }

    /**
     * Robi Transfer LVLi z klasa1 na klasa2
     * @param $dane
     * @param $serwer
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function MakeTransfer(array $dane, int $serwer) : void
    {
        $data = date("Y-m-d");
        $staraklasa = $_POST['old'];
        $nowaklasa = $_POST['new'];

        $makeTranfer = new makeTransfer();
        $makeTranfer->tryAdd($dane, null, $serwer);

        $nazwaserwera = Service::FormatujNazweSerwera($serwer);
        $twig = TwigHelper::getTwig();
        echo $twig->render('buy_transfer.html.twig', [
            'nazwaserwera' => $nazwaserwera,
            'staraklasa'=>$staraklasa,
            'nowaklasa'=>$nowaklasa
        ]);

        new Log($dane['nazwa'], $_SESSION['steam_personaname'], $_SESSION['steamid32'], $data, $data, $nazwaserwera);
    }

    /**
     * Stwarza tytuł strony
     * @param $custom
     */
    public static function buildTitle(string $custom) : void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if(isSet($_GET['id']) && $id)
        {
            $title = Page::loadServiceTitle($_GET['id']);
            echo '<title>' . $title . '</title>';
        }
        else if(isSet($_POST['serviceid']) && is_int($_POST['serviceid']))
        {
            $title = Page::loadServiceTitle($_POST['serviceid']);
            echo '<title>' . $title . '</title>';
        }
        else
        {
            echo '<title>' . $custom . '</title>';
        }
    }

    /**
     * Zwraca tytuł usługi
     * @param $idsrv
     * @return mixed
     */
    private static function loadServiceTitle(int $idsrv) : ?string
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT `nazwa` FROM `services` WHERE `id` = :idsrv");
        $sth->bindParam(':idsrv', $idsrv, PDO::PARAM_INT);
        $sth->execute();
        if($row = $sth->fetch())
        {
            $nazwa = $row['nazwa'];
            $pdo = null;
            return $nazwa;
        }
        return null;
    }

    /**
     * Buduje footer strony
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function buildFooter() : void
    {
        $twig = TwigHelper::getTwig();
        echo $twig->render('footer.html.twig');
    }
}
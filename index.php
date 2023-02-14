<?php
//==============================================================================
// index.php for Museum Of Soul //

// Create f3 object then set various global properties of it
// These are available to the routing code below, but also to any
// classes defined in autoloaded definitions

$home = '/home/'.get_current_user();


$f3 = require($home.'/AboveWebRoot/fatfree-master/lib/base.php');

// autoload Controller class(es) and anything hidden above web root, e.g. DB stuff
$f3->set('AUTOLOAD','autoload/;'.$home.'/AboveWebRoot/autoload/');

$db = DatabaseConnection::connect(); // defined as autoloaded class in AboveWebRoot/autoload/
$f3->set('DB', $db);

$f3->set('DEBUG',3);		// set maximum debug level
$f3->set('UI','ui/');		// folder for View templates
//==============================================================================
// Museum Of Soul URL application routings

//home page (index.html) -- actually just shows form entry page with a different title
$f3->route('GET /',
    function ($f3)
    {
        $f3->set('html_title','The Museum of Soul');
        $f3->set('content','home.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
// When using GET, provide a questionnaire for users to retrieve the suitable art collection
$f3->route('GET /quiz',
    function($f3)
    {
        $f3->set('html_title','Personality Quiz');
        $f3->set('content','quizForm.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
// When using POST (e.g.  form is submitted), invoke the controller (SimpleController and SimpleControllerAjax),
// which will process the data of quiz, then return art collection based on the user data.
// We display the art collection via the quizResponse.html

$f3->route('POST /quizForm',
    function($f3) {
        $formdata = array();            // array to pass on the entered data in
        $formdata["name"] = $f3->get('POST.name');            // whatever was called "name" on the form
        $formdata["MBTI"] = $f3->get('POST.MBTI');          // whatever was called "MBTI" on the form
        (int)$formdata["ratingQ1"] = $f3->get('POST.ratingQ1');
        (int)$formdata["ratingQ2"] = $f3->get('POST.ratingQ2');
        (int)$formdata["ratingQ3"] = $f3->get('POST.ratingQ3');
        (int)$formdata["ratingQ4"] = $f3->get('POST.ratingQ4');
        (int)$formdata["ratingQ5"] = $f3->get('POST.ratingQ5');
        (int)$formdata["ratingQ6"] = $f3->get('POST.ratingQ6');
        (int)$formdata["ratingQ7"] = $f3->get('POST.ratingQ7');
        (int)$formdata["ratingQ8"] = $f3->get('POST.ratingQ8');
        (int)$formdata["ratingQ9"] = $f3->get('POST.ratingQ9');
        (int)$formdata["ratingQ10"] = $f3->get('POST.ratingQ10');
        $formdata["questionnaire1"] = $f3->get('POST.questionnaire1');
        $formdata["questionnaire2"] = $f3->get('POST.questionnaire2');
        $formdata["questionnaire3"] = $f3->get('POST.questionnaire3');
        $formdata["questionnaire4"] = $f3->get('POST.questionnaire4');

        $controller = new SimpleController('quizData');
        $controller->putIntoDatabase($formdata);

        // Total score of the survey is calculated automatically by SQL query at 'quizForm'.
        // A score rank will be assigned by SQl query to the user.

        // Retrieve the scoreBand of the user from 'quizForm' of database
        $userData = $controller->getUserTableFromStr($formdata["name"]);
        $scoreBand = $userData["scoreBand"];


        // Retrieve the art collection from 'artCollection' at database, according to the MBTI type and scoreBand of user
        $controllerAjax = new SimpleControllerAjax;
        $alldata = $controllerAjax->artSearching('MBTI', $formdata["MBTI"], 'scoreBand',$scoreBand);
        // Retrieve one art collection randomly for the user
        shuffle($alldata);
        $record = $alldata[0];

        $f3->set('formData', $formdata);        // set info in F3 variable for access in response template
        $f3->set("dbData", [$record]);          // set info in F3 variable for access in filtered data of 'artCollection'
        $f3->set('html_title', 'Quiz Response');
        $f3->set('content', 'quizResponse.html');
        echo template::instance()->render('layout.html');
    }
);

//==============================================================================
// When using GET, provide the art collection for the user.
$f3->route('GET /collection',
    function($f3) {
        $controller = new SimpleControllerAjax;
        $alldata = $controller->getData();

        $f3->set("dbData", $alldata);
        $f3->set('html_title','The Art Collection');
        $f3->set('content','artView.html');
        echo template::instance()->render('layout.html');
    }
);

//==============================================================================
// When using POST, user search the art collection by text and field, including name, MBTI and artist.
$f3->route('POST /collection',
    function($f3) {
        $controller = new SimpleControllerAjax;
        $alldata = $controller->search($f3->get('POST.field'), $f3->get('POST.term'));

        $f3->set("dbData", $alldata);
        $f3->set('html_title','The Art Collection');
        $f3->set('content','artView.html');
        echo template::instance()->render('layout.html');
    }
);

//==============================================================================
// When using GET, provide the About page for the user
$f3->route('GET /aboutUs',
    function($f3)
    {
        $f3->set('html_title','This Art Therapy');
        $f3->set('content','aboutUs.html');
        echo template::instance()->render('layout.html');
    }
);


//==============================================================================
function pprint_var($var)
{
    ob_start();
    var_dump($var);
    return ob_get_clean();
}

$f3->set('ONERROR', // what to do if something goes wrong.
    function($f3) {
        $f3->set('html_title',$f3['ERROR']['code']);
        $f3->set('DUMP', pprint_var($f3['ERROR']));
        $f3->set('content','error.html');
        echo template::instance()->render('layout.html');
    }
);

//==============================================================================
// Run the FFF engine //
$f3->run();

<?php

namespace App\Http\Controllers;

use App\Models\Guess;
use Illuminate\Http\Request;

class GuessController extends Controller
{
    public function index()
    {
        $guesses = Guess::latest()->get();
        return view('Guess.index', [
            'guesses' => $guesses]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'guess' => 'required|max:100']);

        $initialInput = $request->guess;
        $result = $this->checkInput($initialInput);
        Guess::create([
            'guess' => $initialInput,
            'correct' => $result
        ]);
        
        return back();
    }

    private function checkInput($input){
        $x = array("a", "e", "i", "o", "u");
        $counter = 0;
        $input = strtolower($input);
        $input = str_split($input);
        $result = true;
        $input = array_diff($input, ["#"]);
        foreach ($input as $char) {
            if (in_array($char, $x)) {
                unset($input[$counter]);
                $counter++;
            } else if ($char == "b") {
                $input = implode("", $input);

                if ($input == "baguette") {
                    break;
                } else {
                    $result = false;
                    break;
                }
            } else {
                if (end($input) == "!") {
                    break;
                } else {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }
}

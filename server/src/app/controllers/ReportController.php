<?php

class ReportController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$numberOfGames = count(Game::all());
		
		$averageProblemArray = GamePlayer::averageProblemTime(null);
		
		$categories = array();
		foreach(ProblemType::getNombres() as $item){
			array_push($categories, $item->type);
		}

		$players = Player::all();
		
		return View::make('reports.index')
					->with("numberOfGames", $numberOfGames)
					->with("averageData", json_encode($averageProblemArray))
					->with("averageCategories", json_encode($categories))
					->with("players", $players);
	}

	public function getPlayer(){
		
		$player_id = Input::get("player_id");
		$player = Player::find($player_id);
		$averageProblemArray = GamePlayer::averageProblemTime($player_id);
		$categories = array();
		foreach(ProblemType::getNombres() as $item){
			array_push($categories, $item->type);
		}


		return View::make('reports.player')
					->with("player", $player)
					->with("averageData", json_encode($averageProblemArray))
					->with("averageCategories", json_encode($categories));
	}






}

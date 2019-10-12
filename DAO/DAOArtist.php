<?php 
	namespace DAO;

	use DAO\IDAOArtist as IDAOArtist;
	use Model\Artist as Artist;

	class DAOArtist implements IDAOArtist {

		private $artistList;


	 	public function __construct()
	 	{
	 		if(!isset($_SESSION['ArtistRepository']))
	 			$_SESSION['ArtistRepository']=array();

			$this->artistList = &$_SESSION['ArtistRepository'];
	 	}

	 	public function add(Artist $artist)
	 	{
			array_push($this->artistList, $artist);
		}

		public function getAll()
		{
			try
	        {
		 		return $this->artistList;
		 	}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}
		public function getAllActives()
		{
			try
	        {
		 		$list = array();

		 		foreach ($this->artistList as $artist) {
		 			if($artist->getState()==0)
		 				array_push($list, $artist);
		 		}

		 		return $list;
		 	}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}

		public function getById($id)
		{
			try
	        {
			 	$resp = NULL;

			 	foreach ($this->artistList as $artist ) {
			 		if($artist->getId()==$id){
			 			$resp=$artist;
			 			break;
			 		}
			 	}
			 	return $resp;
			}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}

		public function getByName($name)
		{
			try
	        {
			 	$resp = NULL;

			 	foreach ($this->artistList as $artist ) {
			 		if($artist->getName()==$name){
			 			$resp=$artist;
			 			break;
			 		}
			 	}
			 	return $resp;
		 	}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}

		public function delete($id)
		{
			try
	        {
				$i = 0;

				foreach ($this->artistList as $artist) 
				{
					if($id == $artist->getId())
					{
						$state = $this->artistList[$i]->getState();

						if($state == 0)
							$this->artistList[$i]->setState(1);
						else
							$this->artistList[$i]->setState(0);
						break;
					}
					$i++;
				}
			}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}

		public function update(Artist $artist)
		{
			try
	        {
	        	$id = $artist->getId();
				$i = 0;

				foreach ($this->artistList as $art) 
				{
					if($id == $art->getId())
					{
						$this->artistList[$i] = $artist;
						break;
					}
					$i++;
				}
			}
	        catch(Exception $e)
	        {
	            throw $e;
	        }
		}

	}

?>
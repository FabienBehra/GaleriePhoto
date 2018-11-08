<?php

  # Notion d'image
  class Image {
    private $url="";
    private $id=0;
    private $category="";
    private $description="";

    function __construct($u,$id,$cat,$desc) {
      $this->url = $u;
      $this->id = $id;
      $this->category = $cat;
      $this->description = $desc;
    }

    # Retourne l'URL de cette image
    function getURL() {
		return $this->url;
    }
    function getId() {
      return $this->id;
    }
    function getCategory() {
      return $this->category;
    }
    function getDescription() {
      return $this->description;
    }
  }


?>

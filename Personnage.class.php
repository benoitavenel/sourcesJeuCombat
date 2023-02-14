<?php
abstract class Personnage
{
    protected $atout,
        $degats,
        $id,
        $nom,
        $timeEndormi,
        $type;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
        $this->type = strtolower(get_class($this));
     
    }

    public function hydrate(array $donnees)
    {
      foreach ($donnees as $key => $value)
      {
        $method = 'set'.ucfirst($key);
        
        if (method_exists($this, $method))
        {
          $this->$method($value);
        }
      }
    }

    public function estEndormi()
    {
        return $this->timeEndormi > time();
    }

    public function frapper(Personnage $perso)
    {
        if ($perso->id == $this->id) {
            return "ErreurCible";
        }

        if ($this->estEndormi()) {
            return "PersonneEndormie";
        }

        // On indique au personnage qu'il doit recevoir des dégâts.
        return $perso->recevoirDegats();
    }

    public function nomValide()
    {
        return !empty($this->nom);
    }

    public function recevoirDegats()
    {
        $this->degats += 5;

        // Si on a 100 de dégâts ou plus, on supprime le personnage de la BDD.
        if ($this->degats >= 100) {
            return "PersonneTuee";
        }

        // Sinon, on se contente de mettre à jour les dégâts du personnage.
        return "PersonneFrappee";
    }

    public function reveil()
    {
        $secondes = $this->timeEndormi;
        $secondes -= time();

        $heures = floor($secondes / 3600);
        $secondes -= $heures * 3600;
        $minutes = floor($secondes / 60);
        $secondes -= $minutes * 60;

        $heures .= $heures <= 1 ? ' heure' : ' heures';
        $minutes .= $minutes <= 1 ? ' minute' : ' minutes';
        $secondes .= $secondes <= 1 ? ' seconde' : ' secondes';

        return $heures . ', ' . $minutes . ' et ' . $secondes;
    }

    public function atout()
    {
        return $this->atout;
    }

    public function degats()
    {
        return $this->degats;
    }

    public function id()
    {
        return $this->id;
    }

    public function nom()
    {
        return $this->nom;
    }

    public function timeEndormi()
    {
        return $this->timeEndormi;
    }

    public function type()
    {
        return $this->type;
    }

    public function setAtout($atout)
    {
        $atout = (int) $atout;

        if ($this->degats >= 0 && $this->degats <= 25) {
            $this->atout = 4;
        } elseif ($this->degats > 25 && $this->degats <= 50) {
            $this->atout = 3;
        } elseif ($this->degats > 50 && $this->degats <= 75) {
            $this->atout = 2;
        } elseif ($this->degats > 75 && $this->degats <= 90) {
            $this->atout = 1;
        } else {
            $this->atout = 0;
        }
    }

    public function setDegats($degats)
    {
        $degats = (int) $degats;

        if ($degats >= 0 && $degats <= 100) {
            $this->degats = $degats;
        }
    }

    public function setId($id)
    {
        $id = (int) $id;

        if ($id > 0) {
            $this->id = $id;
        }
    }

    public function setNom($nom)
    {
        if (is_string($nom)) {
            $this->nom = $nom;
        }
    }

    public function setTimeEndormi($time)
    {
        $this->timeEndormi = (int) $time;
    }
}

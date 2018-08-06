<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * ClientNote
 *
 * @ORM\Table(name="client_note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientNoteRepository")
 */
class ClientNote
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="clientNotes");
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return ClientNote
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set client
     *
     * @param string $client
     *
     * @return ClientNote
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }
}


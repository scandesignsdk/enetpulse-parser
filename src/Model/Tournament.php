<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model;

use SDM\Enetpulse\Model\Tournament\TournamentStage;
use SDM\Enetpulse\Model\Tournament\TournamentTemplate;

class Tournament
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var null|TournamentStage[]
     */
    private $stages;

    /**
     * @var TournamentTemplate
     */
    private $template;

    /**
     * @var array|Odds[]|null
     */
    private $odds;

    /**
     * @param int                    $id
     * @param string                 $name
     * @param TournamentTemplate     $template
     * @param TournamentStage[]|null $stages
     */
    public function __construct(int $id, string $name, TournamentTemplate $template, array $stages = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->template = $template;
        $this->setStages($stages);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TournamentTemplate
     */
    public function getTemplate(): TournamentTemplate
    {
        return $this->template;
    }

    /**
     * @return null|TournamentStage[]
     */
    public function getStages(): ?array
    {
        return $this->stages;
    }

    public function setStages(array $stages = null): self
    {
        $this->stages = [];
        if ($stages) {
            foreach ($stages as $stage) {
                $this->addStage($stage);
            }
        }

        return $this;
    }

    public function addStage(TournamentStage $stage): self
    {
        $this->stages[] = $stage;

        return $this;
    }
}

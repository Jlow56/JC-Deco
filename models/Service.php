<?php

class Service
{
    private ? int $id = null;
    private array $medias = [];


    public function __construct(private string $title1, private string $title2, private  string $title3, private string $content)
    {

    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

      /**
     * @return string
     */
    public function getTitle1(): string
    {
        return $this->title1;
    }

    /**
     * @param string $title1
     */
    public function setTitle1(string $title1): void
    {
        $this->title1 = $title1;
    }

    /**
     * @return string
     */
    public function getTitle2(): string
    {
        return $this->title2;
    }
    /**
     * @param string $title2
     */
    public function setTitle2(string $title2): void
    {
        $this->title2 = $title2;
    }

    /**
     * @return string
     */
    public function getTitle3(): string
    {
        return $this->title3;
    }
    /**
     * @param string $title3
     */
    public function setTitle3(string $title3): void
    {
        $this->title3 = $title3;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    /**
    * @return string
    */
    public function getMedias(): array
    {
        return $this->medias;
    }
    /**
     * @param string $medias
     */
    public function setMedia(array $medias): void
    {
        $this->medias = $medias;
    }
}
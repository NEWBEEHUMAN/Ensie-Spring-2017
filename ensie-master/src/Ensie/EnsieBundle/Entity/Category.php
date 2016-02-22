<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CategoryRepository")
 * @Vich\Uploadable
 */
class Category
{
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Sluggable\Sluggable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Subcategory", mappedBy="category")
     */
    protected $subcategories;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\Ensie\LanguageBundle\Entity\Language")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @Vich\UploadableField(mapping="category_header", fileNameProperty="headerImage")
     */
    private $headerImageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="header_image", type="string", length=255)
     */
    private $headerImage;

    /**
     * @var string
     *
     * @Vich\UploadableField(mapping="category_tile", fileNameProperty="tileImage")
     */
    private $tileImageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="tile_image", type="string", length=255)
     */
    private $tileImage;

    /**
     * @var string
     *
     * @Vich\UploadableField(mapping="category_logo", fileNameProperty="logoImage")
     */
    private $logoImageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_image", type="string", length=255)
     */
    private $logoImage;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Category
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $headerImageFile
     */
    public function setHeaderImageFile(File $headerImageFile)
    {
        $this->headerImageFile = $headerImageFile;

        if ($headerImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getHeaderImageFile()
    {
        return $this->headerImageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $tileImageFile
     */
    public function setTileImageFile(File $tileImageFile)
    {
        $this->tileImageFile = $tileImageFile;

        if ($tileImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getTileImageFile()
    {
        return $this->tileImageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $logoImageFile
     */
    public function setLogoImageFile(File $logoImageFile)
    {
        $this->logoImageFile = $logoImageFile;

        if ($logoImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getLogoImageFile()
    {
        return $this->logoImageFile;
    }

    public function getSluggableFields()
    {
        return [ 'title' ];
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
    }

    /**
     * @return string
     */
    public function getLogoImage()
    {
        return $this->logoImage;
    }

    /**
     * @param string $logoImage
     */
    public function setLogoImage($logoImage)
    {
        $this->logoImage = $logoImage;
    }

    /**
     * @return string
     */
    public function getTileImage()
    {
        return $this->tileImage;
    }

    /**
     * @param string $tileImage
     */
    public function setTileImage($tileImage)
    {
        $this->tileImage = $tileImage;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }
}


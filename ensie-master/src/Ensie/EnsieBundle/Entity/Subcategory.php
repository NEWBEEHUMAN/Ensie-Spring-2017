<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Subcategory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SubcategoryRepository")
 * @Vich\Uploadable
 */
class Subcategory
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
     * @ORM\OneToMany(targetEntity="Ensie\EnsieBundle\Entity\Definition", mappedBy="subcategory")
     */
    protected $definitions;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="subcategories")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

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
     * @Vich\UploadableField(mapping="subcategory_header", fileNameProperty="headerImage")
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
     * @Vich\UploadableField(mapping="subcategory_tile", fileNameProperty="tileImage")
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
     * @Vich\UploadableField(mapping="subcategory_logo", fileNameProperty="logoImage")
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
     * @return Subcategory
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
     * @return Subcategory
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
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
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
     * @param string $tileImage
     */
    public function setTileImage($tileImage)
    {
        $this->tileImage = $tileImage;
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

    /**
     * @param string $logoImage
     */
    public function setLogoImage($logoImage)
    {
        $this->logoImage = $logoImage;
    }

    public function getSluggableFields()
    {
        return [ 'title' ];
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * @return string
     */
    public function getTileImage()
    {
        return $this->tileImage;
    }

    /**
     * @return string
     */
    public function getLogoImage()
    {
        return $this->logoImage;
    }

    public function __toString()
    {
        if ($this->category) {
            return $this->category->getTitle() . ' > ' . $this->title;
        }
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }
}


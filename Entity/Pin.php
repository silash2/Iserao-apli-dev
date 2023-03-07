<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PinRepository;
use App\Entity\Traits\Timestampable;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=PinRepository::class)
 * @ORM\Table(name = "pins")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */


 class Pin
                                                                                                                                     {
                                                                                                                                         use Timestampable;
                                                                                                                                     
                                                                                                                                    
                                                                                                                                    
                                                                                                                                        /**
                                                                                                                                         * @ORM\Id
                                                                                                                                         * @ORM\GeneratedValue
                                                                                                                                         * @ORM\Column(type="integer")
                                                                                                                                         */
                                                                                                                                        private $id;
                                                                                                                                    
                                                                                                                                        /**
                                                                                                                                         * @ORM\Column(type="string", length=255)
                                                                                                                                         */
                                                                                                                                        private $title;
                                                                                                                                    
                                                                                                                                        /**
                                                                                                                                         * @ORM\Column(type="text")
                                                                                                                                         */
                                                                                                                                        private $description;
                                                                                                                                        
                                                                                                                                         /**
                                                                                                                                         * @Vich\UploadableField(mapping="pin_image", fileNameProperty="imageName")
                                                                                                                                         * @var File|null
                                                                                                                                         * @Assert\Image(maxSize="10M")
                                                                                                                                         */
                                                                                                                                        private $imageFile;
                                                                                                                                    
                                                                                                                                        /**
                                                                                                                                         * @ORM\Column(type="string", length=255, nullable=true)
                                                                                                                                         */
                                                                                                                                        private $imageName;
                                                                                                                                 
                                                                                                                                        /**
                                                                                                                                         * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pins")
                                                                                                                                         * @ORM\JoinColumn(nullable=false)
                                                                                                                                         */
                                                                                                                                        private $user;
                                                                              
                                                                                                                                        /**
                                                                                                                                         * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="pins", orphanRemoval=true)
                                                                                                                                         */
                                                                                                                                        private $comments;
      
                                                                                                                                        /**
                                                                                                                                         * @ORM\ManyToOne(targetEntity=Boutique::class, inversedBy="publication")
                                                                                                                                         */
                                                                                                                                        private $boutique;
                     
                                                                                                                                      
                                                                  
                                                                                                 
                                                                                                                                    
                                                                                             
                                                                                                                                        public function __construct()
                                                                                                                                        {
                                                                                                                                            $this->comments = new ArrayCollection();
                                                                                                                                           
                                                                                                                                        }
                                                                                                                  
                                                                                                                                       
                                                                                                               
                                                                                                                                    
                                                                                                                                    
                                                                                                                                        public function getId(): ?int
                                                                                                                                        {
                                                                                                                                            return $this->id;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function getTitle(): ?string
                                                                                                                                        {
                                                                                                                                            return $this->title;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function setTitle(string $title): self
                                                                                                                                        {
                                                                                                                                            $this->title = $title;
                                                                                                                                    
                                                                                                                                            return $this;
                                                                                                                                        }
                                                                                                                                         /**
                                                                                                                                         *
                                                                                                                                         * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
                                                                                                                                         */
                                                                                                                                        public function setImageFile(?File $imageFile = null): void
                                                                                                                                        {
                                                                                                                                            $this->imageFile = $imageFile;
                                                                                                                                    
                                                                                                                                            if (null !== $imageFile) {
                                                                                                                                                // It is required that at least one field changes if you are using doctrine
                                                                                                                                                // otherwise the event listeners won't be called and the file is lost
                                                                                                                                                
                                                                                                                                                $this->setUpdatedAt(new \DateTime);
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function getImageFile(): ?File
                                                                                                                                        {
                                                                                                                                            return $this->imageFile;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function getDescription(): ?string
                                                                                                                                        {
                                                                                                                                            return $this->description;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function setDescription(string $description): self
                                                                                                                                        {
                                                                                                                                            $this->description = $description;
                                                                                                                                    
                                                                                                                                            return $this;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function getImageName(): ?string
                                                                                                                                        {
                                                                                                                                            return $this->imageName;
                                                                                                                                        }
                                                                                                                                    
                                                                                                                                        public function setImageName(?string $imageName): self
                                                                                                                                        {
                                                                                                                                            $this->imageName = $imageName;
                                                                                                                                    
                                                                                                                                            return $this;
                                                                                                                                        }
                                                                                                                              
                                                                                                                                        public function getUser(): ?User
                                                                                                                                        {
                                                                                                                                            return $this->user;
                                                                                                                                        }
                                                                                                                           
                                                                                                                                        public function setUser(?User $user): self
                                                                                                                                        {
                                                                                                                                            $this->user = $user;
                                                                                                                           
                                                                                                                                            return $this;
                                                                                                                                        }
                                                                           
                                                                                                                                        /**
                                                                                                                                         * @return Collection<int, Comment>
                                                                                                                                         */
                                                                                                                                        public function getComments(): Collection
                                                                                                                                        {
                                                                                                                                            return $this->comments;
                                                                                                                                        }
                                                                        
                                                                                                                                        public function addComment(Comment $comment): self
                                                                                                                                        {
                                                                                                                                            if (!$this->comments->contains($comment)) {
                                                                                                                                                $this->comments[] = $comment;
                                                                                                                                                $comment->setPins($this);
                                                                                                                                            }
                                                                        
                                                                                                                                            return $this;
                                                                                                                                        }
                                                                     
                                                                                                                                        public function removeComment(Comment $comment): self
                                                                                                                                        {
                                                                                                                                            if ($this->comments->removeElement($comment)) {
                                                                                                                                                // set the owning side to null (unless already changed)
                                                                                                                                                if ($comment->getPins() === $this) {
                                                                                                                                                    $comment->setPins(null);
                                                                                                                                                }
                                                                                                                                            }
                                                                     
                                                                                                                                            return $this;
                                                                                                                                        }
   
                                                                                                                                        public function getBoutique(): ?Boutique
                                                                                                                                        {
                                                                                                                                            return $this->boutique;
                                                                                                                                        }

                                                                                                                                        public function setBoutique(?Boutique $boutique): self
                                                                                                                                        {
                                                                                                                                            $this->boutique = $boutique;

                                                                                                                                            return $this;
                                                                                                                                        }
               
                          
                                                               
                        
                                                                                       
                                                                                                         
                                                                                                                                       
                                                                                                                                   
                                                                                                                                    }

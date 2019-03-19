<?php declare(strict_types=1);

/**
 *
 * part-db version 0.1
 * Copyright (C) 2005 Christoph Lechner
 * http://www.cl-projects.de/
 *
 * part-db version 0.2+
 * Copyright (C) 2009 K. Jacobs and others (see authors.php)
 * http://code.google.com/p/part-db/
 *
 * Part-DB Version 0.4+
 * Copyright (C) 2016 - 2019 Jan Böhmer
 * https://github.com/jbtronics
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 *
 */

namespace App\Entity;

use App\Entity\Embeddables\PermissionEntity;
use App\Security\Interfaces\HasPermissionsInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This entity represents a user, which can log in and have permissions.
 * Also this entity is able to save some informations about the user, like the names, email-address and other info.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("users")
 */
class User extends NamedDBElement implements UserInterface, HasPermissionsInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * //@ORM\Column(type="json")
     */
    //protected $roles = [];

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @var string|null The first name of the User
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $first_name = "";

    /**
     * @var string|null The last name of the User
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    protected $last_name = "";

    /**
     * @var string|null The department the user is working
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $department = "";


    /**
     * @var string|null The email address of the user
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    protected $email = "";

    /**
     * @var string|null The language/locale the user prefers
     * @ORM\Column(type="string", name="config_language", nullable=true)
     */
    protected $language = "";

    /**
     * @var string|null The timezone the user prefers
     * @ORM\Column(type="string", name="config_timezone", nullable=true)
     */
    protected $timezone = "";

    /**
     * @var string|null The theme
     * @ORM\Column(type="string", name="config_theme", nullable=true)
     */
    protected $theme = "";

    /**
     * @var Group|null The group this user belongs to.
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users", fetch="EAGER")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /** @var PermissionsEmbed
     * @ORM\Embedded(class="PermissionsEmbed", columnPrefix="perms_")
     */
    protected $permissions;


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->name;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        //$roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        //$this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     * Gets the password hash for this entity.
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Sets the password hash for this user.
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Returns the ID as an string, defined by the element class.
     * This should have a form like P000014, for a part with ID 14.
     * @return string The ID as a string;
     */
    public function getIDString(): string
    {
        return 'U' . sprintf('%06d', $this->getID());
    }


    public function getPermissions() : PermissionsEmbed
    {
        return $this->permissions;
    }

    /************************************************
     * Getters
     ************************************************/

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return User
     */
    public function setFirstName(?string $first_name): User
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return User
     */
    public function setLastName(?string $last_name): User
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @param string $department
     * @return User
     */
    public function setDepartment(?string $department): User
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return User
     */
    public function setLanguage(?string $language): User
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return User
     */
    public function setTimezone(?string $timezone): User
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTheme(): ?string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     * @return User
     */
    public function setTheme(?string $theme): User
    {
        $this->theme = $theme;
        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;
        return $this;
    }

}
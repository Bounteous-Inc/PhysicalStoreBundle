<?php
/**
 * Created by PhpStorm.
 * User: rodolfobandeira
 * Date: 2015-08-10
 * Time: 12:43 PM
 */

namespace DemacMedia\Bundle\PhysicalStoreBundle\Entity\Manager;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreAccounts;

class OroPhysicalStoreAccountsApiManager
{
    /**
     * @param Comment $entity
     * @param string  $entityClass
     * @param string  $entityId
     *
     * @return array
     */
    public function getEntityViewModel(OroPhysicalStoreAccounts $entity, $entityClass = '', $entityId = '')
    {
        $ownerName = '';
        $ownerId   = '';
        if ($entity->getOwner()) {
            $ownerName = $this->entityNameResolver->getName($entity->getOwner());
            $ownerId   = $entity->getOwner()->getId();
        }
        $editorName = '';
        $editorId   = '';
        if ($entity->getUpdatedBy()) {
            $editorName = $this->entityNameResolver->getName($entity->getUpdatedBy());
            $editorId   = $entity->getUpdatedBy()->getId();
        }
        $result = [
            'id'            => $entity->getId(),
            'owner'         => $ownerName,
            'owner_id'      => $ownerId,
            'editor'        => $editorName,
            'editor_id'     => $editorId,
            'message'       => $entity->getMessage(),
            'relationClass' => $entityClass,
            'relationId'    => $entityId,
            'createdAt'     => $entity->getCreatedAt()->format('c'),
            'updatedAt'     => $entity->getUpdatedAt()->format('c'),
            'editable'      => $this->securityFacade->isGranted('EDIT', $entity),
            'removable'     => $this->securityFacade->isGranted('DELETE', $entity),
        ];
        $result = array_merge($result, $this->getAttachmentInfo($entity));
        $result = array_merge($result, $this->getCommentAvatarImageUrl($entity->getOwner()));
        return $result;
    }
}

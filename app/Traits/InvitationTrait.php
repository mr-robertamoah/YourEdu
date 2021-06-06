<?php

namespace App\Traits;

trait InvitationTrait
{
    private function setItemModel($invitationDTO)
    {
        if (is_not_null($invitationDTO->itemModel)) {
            return $invitationDTO;
        }

        return $invitationDTO->withItemModel(
            $this->getModel($invitationDTO->item, $invitationDTO->itemId)
        );
    }

    private function setJoiner($invitationDTO)
    {
        if (is_not_null($invitationDTO->joiner)) {
            return $invitationDTO;
        }

        return $invitationDTO->withJoiner(
            $this->getModel($invitationDTO->account, $invitationDTO->accountId)
        );
    }

    private function setInvitee($invitationDTO)
    {
        if (is_not_null($invitationDTO->invitee)) {
            return $invitationDTO;
        }

        return $invitationDTO->withInvitee(
            $this->getModel($invitationDTO->account, $invitationDTO->accountId)
        );
    }

}

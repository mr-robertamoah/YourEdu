<?php

namespace App\Services;

use App\YourEdu\Facilitator;
use App\YourEdu\Request;
use \Debugbar;

class FacilitatorService
{
    /**
     * send a request to facilitator to join school|
     * 
     * @param Facilitator $facilitator
     * @param $sender
     * @param array $requestDetails
     * 
     * @return Request
     */
    public function sendFacilitatingRequest(Facilitator $facilitator,$sender,$requestDetails)
    {
        $request = $sender->requestsSent()->create([
            'state' => 'PENDING'
        ]);
        $request->requestto()->associate($facilitator);

        $data = [];
        $data['salary'] = $requestDetails['salary'];
        if (count($requestDetails['files'])) {
            foreach ($requestDetails['files'] as $requestFile) {
                $file = FileService::createAndAttachFiles(
                    account: $sender,
                    file: $requestFile,
                    item: $request
                );
                $data['file'][] = [
                    'id' => $file->id, 
                    'type' => class_basename_lower(get_class($file))
                ];
            }
        }

        $request->data = serialize($data);
        $request->save();

        return $request;
    }
}
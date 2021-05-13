<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetsReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'name'      => 'Assets',
                'balance'   => sprintf('%.2f', $this->getBalance()),
                'groups'    => $this->buildGroups(),
                'accounts'  => $this->buildAccounts(),
            ]
        ];
    }

    private function buildGroups()
    {
        return array_map(function ($group) {
            return [
                'id' => $group->getId(),
                'name' => $group->getName()
            ];
        }, $this->getGroups());
    }

    private function buildAccounts()
    {
        return array_reduce($this->getGroups(), function ($result, $group) {
            $entries = array_map(function ($entry) use ($group) {
                return  [
                    'id'        => $entry->getId(),
                    'name'      => $entry->getName(),
                    'balance'   => sprintf('%.2f', $entry->getCurrentBalance()),
                    'change'    => sprintf('%.2f', $entry->getChangePercent()),
                    'group_id'  => $group->getId(),
                ];
            }, $group->getEntries());

            return array_merge($result, $entries);
        }, []);
    }
}

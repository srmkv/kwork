<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\SettingMainPage\FooterService;

class FooterController extends Controller
{
    public function __construct(FooterService $footer)
    {
        $this->footer = $footer;
    }

    public function createSectionFooter(Request $request)
    {
        return $this->footer->createSection($request->all());
    }

    public function updateSectionFooter(Request $request, $id)
    {
        return $this->footer->updateSection($request->all(), $id);
    }

    public function deleteSectionFooter(Request $request, $id)
    {
        return $this->footer->deleteSection($id);
    }

    public function getSectionsFooter(Request $request)
    {
        return $this->footer->all();
    }

    public function createSectionFooterItem(Request $request, $id)
    {
        return $this->footer->createItem($request->all(), $id);
    }

    public function updateSectionFooterItem(Request $request, $id)
    {
        return $this->footer->updateItem($request->all(), $id);
    }

    public function deleteSectionFooterItem(Request $request, $id)
    {
        return $this->footer->deleteItem($id);
    }

    public function createTopLogo(Request $request)
    {
        return $this->footer->newTopLogo();
    }

    public function updateTopLogo(Request $request, $id)
    {
        return $this->footer->updateTopLogo($request->all(), $id);
    }

    public function deleteTopLogo(Request $request, $id)
    {
        return $this->footer->deleteTopLogo($id);
    }

    public function loadTopLogo(Request $request, $id)
    {
        return $this->footer->loadTopLogo($request->file('file'), $id);
    }

    public function allTopLogo(Request $request)
    {
        return $this->footer->allTopLogo();
    }


    public function createBottomLogo(Request $request)
    {
        return $this->footer->newBottomLogo();
    }

    public function updateBottomLogo(Request $request, $id)
    {
        return $this->footer->updateBottomLogo($request->all(), $id);
    }

    public function deleteBottomLogo(Request $request, $id)
    {
        return $this->footer->deleteBottomLogo($id);
    }

    public function loadBottomLogo(Request $request, $id)
    {
        return $this->footer->loadBottomLogo($request->file('file'), $id);
    }

    public function allBottomLogo(Request $request)
    {
        return $this->footer->allBottomLogo();
    }

    public function createSocial(Request $request)
    {
        return $this->footer->newSocial($request->all());
    }

    public function updateSocial(Request $request, $id)
    {
        return $this->footer->updateSocial($request->all(), $id);
    }

    public function deleteSocial(Request $request, $id)
    {
        return $this->footer->deleteSocial($id);
    }

    public function allSocials(Request $request)
    {
        return $this->footer->allSocials();
    }

    public function createBlockRef(Request $request)
    {
        return $this->footer->newBlockRef($request->all());
    }

    public function updateBlockRef(Request $request, $id)
    {
        return $this->footer->updateBlockRef($request->all(), $id);
    }

    public function deleteBlockRef(Request $request, $id)
    {
        return $this->footer->deleteBlockRef($id);
    }

    public function allBlocksRef(Request $request)
    {
        return $this->footer->allBlocksRef();
    }
}

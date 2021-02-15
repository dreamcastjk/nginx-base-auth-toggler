<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use App\Services\DomainService;

/**
 * Class SiteController
 * @package App\Http\Controllers
 */
class SiteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function __invoke()
    {
        return view('nginx.index');
    }

    /**
     * List of all disabled base auth domains.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function list()
    {
        $domains = Domain::with('user')
            ->whereStatus(Domain::STATUS_DISABLED)
            ->paginate();

        return view('nginx.list', compact('domains'));
    }

    /**
     * @param DomainRequest $request
     * @param DomainService $domainService
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function disable(DomainRequest $request, DomainService $domainService)
    {
        $result = $domainService->disable($request->domain);

        return redirect()->back()->with($result['status'], $result['message']);
    }

    /**
     * Enables base auth by given domain.
     *
     * @param Domain $domain
     * @param DomainService $domainService
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function enable(Domain $domain, DomainService $domainService)
    {
        $result = $domainService->enable($domain);

        return redirect()->back()->with($result['status'], $result['message']);
    }
}

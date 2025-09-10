<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Repositories\ClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ClientController extends AppBaseController
{
    /** @var  ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clients = $this->clientRepository->all();

        return view('clients.index')
            ->with('clients', $clients);
    }

    public function clients_list($nit)
    {
        $client = $this->clientRepository->all()->whereInStrict('nit', (string)$nit)->first();
        return $client;
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
    public function store(CreateClientRequest $request)
    {
        $input = $request->all();

        $client = $this->clientRepository->create($input);

        return redirect(route('clients.index'))->with('success', 'Client saved successfully.');


    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            return redirect(route('clients.index'))->with('error', 'Client not found');
        }

        return view('clients.edit')->with('client', $client);
    }

    /**
     * Update the specified Client in storage.
     *
     * @param int $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            return redirect(route('clients.index'))->with('error', 'Client not found');
        }

        $client = $this->clientRepository->update($request->all(), $id);

        return redirect(route('clients.index'))->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified Client from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            return redirect(route('clients.index'))->with('error', 'Client not found');
        }

        $this->clientRepository->delete($id);

        return redirect(route('clients.index'))->with('success', 'Client deleted successfully.');
    }
}

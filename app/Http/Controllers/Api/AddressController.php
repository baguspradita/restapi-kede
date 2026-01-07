<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $addresses = $request->user()->addresses()->orderBy('is_default', 'desc')->get();
            return $this->successResponse($addresses, 'Addresses retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve addresses: ' . $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);
            return $this->successResponse($address, 'Address retrieved successfully');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'label' => 'required|string|max:255',
                'recipient_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'full_address' => 'required|string',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'is_default' => 'boolean',
                'is_selected' => 'boolean',
            ]);

            $validated['user_id'] = $request->user()->id;

            // If setting as default, unset other defaults
            if ($validated['is_default'] ?? false) {
                $request->user()->addresses()->update(['is_default' => false]);
            }

            $address = Address::create($validated);

            return $this->createdResponse($address, 'Address created successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create address: ' . $e->getMessage(), [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            $validated = $request->validate([
                'label' => 'sometimes|required|string|max:255',
                'recipient_name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'full_address' => 'sometimes|required|string',
                'city' => 'sometimes|required|string|max:255',
                'province' => 'sometimes|required|string|max:255',
                'postal_code' => 'sometimes|required|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'is_selected' => 'sometimes|boolean',
            ]);

            $address->update($validated);

            return $this->successResponse($address, 'Address updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);
            $address->delete();

            return $this->successResponse(null, 'Address deleted successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }

    public function setDefault(Request $request, $id)
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            // Unset all defaults
            $request->user()->addresses()->update(['is_default' => false]);

            // Set this as default
            $address->update(['is_default' => true]);

            return $this->successResponse($address, 'Default address set successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }

    public function select(Request $request)
    {
        try {
            $validated = $request->validate([
                'address_ids' => 'required|array|min:1',
                'address_ids.*' => 'integer',
                'selected' => 'required|boolean',
            ]);

            $user = $request->user();

            $ids = $user->addresses()
                ->whereIn('id', $validated['address_ids'])
                ->pluck('id')
                ->all();

            if (empty($ids)) {
                return $this->validationErrorResponse(['address_ids' => ['No valid address IDs provided.']]);
            }

            Address::whereIn('id', $ids)->update(['is_selected' => $validated['selected']]);

            $updated = $user->addresses()->whereIn('id', $ids)->get();

            return $this->successResponse($updated, 'Addresses selection updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update selection: ' . $e->getMessage(), [], 500);
        }
    }

    public function toggleSelect(Request $request, $id)
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);
            $address->is_selected = !$address->is_selected;
            $address->save();

            return $this->successResponse($address, 'Address selection toggled successfully');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }
}

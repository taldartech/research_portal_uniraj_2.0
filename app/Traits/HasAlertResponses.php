<?php

namespace App\Traits;

trait HasAlertResponses
{
    /**
     * Return success response with alert
     */
    protected function successResponse($message, $route = null, $data = [])
    {
        if ($route) {
            return redirect()->route($route, $data)->with('success', $message);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Return error response with alert
     */
    protected function errorResponse($message, $route = null, $data = [])
    {
        if ($route) {
            return redirect()->route($route, $data)->with('error', $message);
        }

        return redirect()->back()->with('error', $message);
    }

    /**
     * Return warning response with alert
     */
    protected function warningResponse($message, $route = null, $data = [])
    {
        if ($route) {
            return redirect()->route($route, $data)->with('warning', $message);
        }

        return redirect()->back()->with('warning', $message);
    }

    /**
     * Return info response with alert
     */
    protected function infoResponse($message, $route = null, $data = [])
    {
        if ($route) {
            return redirect()->route($route, $data)->with('info', $message);
        }

        return redirect()->back()->with('info', $message);
    }

    /**
     * Handle CRUD operations with standardized responses
     */
    protected function handleCrudResponse($operation, $model, $successMessage = null, $errorMessage = null, $route = null)
    {
        try {
            $result = $operation();

            if ($result) {
                $message = $successMessage ?: $this->getDefaultSuccessMessage($model);
                return $this->successResponse($message, $route);
            } else {
                $message = $errorMessage ?: $this->getDefaultErrorMessage($model);
                return $this->errorResponse($message, $route);
            }
        } catch (\Exception $e) {
            $message = $errorMessage ?: $this->getDefaultErrorMessage($model) . ': ' . $e->getMessage();
            return $this->errorResponse($message, $route);
        }
    }

    /**
     * Get default success message based on model
     */
    private function getDefaultSuccessMessage($model)
    {
        $modelName = class_basename($model);
        return ucfirst(strtolower($modelName)) . ' operation completed successfully.';
    }

    /**
     * Get default error message based on model
     */
    private function getDefaultErrorMessage($model)
    {
        $modelName = class_basename($model);
        return 'Failed to complete ' . strtolower($modelName) . ' operation.';
    }
}

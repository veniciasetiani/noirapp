<?php

namespace App\Http\Controllers;
use App\Models\category;
use App\Models\User;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showcategory()
    {
        return view('gamecategory', [
            'title' => "User by category",
            'active' => 'category',
            'categories' => category::all() // Use the correct model name
        ]);
    }

    public function showuserbycategory(Category $category)
    {
        if (!$category) {
            abort(404); // or handle the error in another way
        }

        if (auth()->user()) {
            $users = $category->user()
                ->with(['permissions' => function ($query) {
                    $query->where('statcode', 'APV')->latest('created_at');
                }])
                ->whereIn('role_id', [1, 2])
                ->where('id', '!=', auth()->user()->id)
                ->get();
        } else {
            $users = $category->user()->whereIn('role_id', [1, 2])->get();
        }

        // You can remove the dd() here if you want to proceed to rendering the view
        // dd([
        //     'title' => "User by category",
        //     'active' => 'category',
        //     'users' => $users,
        // ]);

        return view('users', [
            'title' => "User by category",
            'active' => 'category',
            'users' => $users,
            'category' => $category,
        ]);
    }





    public function filterByRole(Request $request, category $category)
    {
        $roleId = $request->input('role');
        $selectedDay = $request->input('day');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $minRating = $request->input('min_rating');
        $maxRating = $request->input('max_rating');

        $users = User::whereHas('role', function ($query) use ($roleId) {
            if ($roleId != 'all') {
                $query->where('id', $roleId);
            }
        });

        if ($category) {
            // If category is provided, filter by category
            $users->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category->slug);
            });
        }
        if ($selectedDay) {
            // If day is selected, filter by day
            $users->whereHas('availableTimes', function ($query) use ($selectedDay) {
                $query->where('day', $selectedDay);
            });
        }

        if ($startTime && $endTime) {
            $users->whereHas('availableTimes', function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->whereBetween('end_time', [$startTime, $endTime]);
            });
        }

        if ($minRating) {
            $users->where('rating_avg', '>=', $minRating);
        }

        if ($maxRating) {
            $users->where('rating_avg', '<=', $maxRating);
        }

        $users = $users->get();

        return view('users', [
            'title' => 'Filtered Users',
            'active' => 'category',
            'users' => $users,
            'category' => $category,
        ]);
    }
}

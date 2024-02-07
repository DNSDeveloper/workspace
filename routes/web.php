<?php

use App\Http\Controllers\Auth\Register2Controller;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});



Auth::routes(['register' => Register2Controller::class, 'register']);
Route::get('/employees/list-employees', [Register2Controller::class, 'index'])->name('register.index');
Route::get('/employees/add-employee', [Register2Controller::class, 'create'])->name('register.create');
Route::post('/employees', [RegisterController::class, 'store'])->name('register.store');
Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware(['auth', 'can:admin-access'])->group(function () {
    Route::get('/', 'AdminController@index')->name('index');
    Route::get('/reset-password', 'AdminController@reset_password')->name('reset-password');
    Route::put('/update-password', 'AdminController@update_password')->name('update-password');
    Route::put('/change-profile', 'AdminController@change_profile')->name('change.profile');

    // Routes for employees //
    Route::get('/employees/list-employees', 'EmployeeController@index')->name('employees.index');
    Route::get('/employees/add-employee', 'EmployeeController@create')->name('employees.create');
    Route::post('/employees', 'EmployeeController@store')->name('employees.store');
    Route::get('/employees/attendance', 'EmployeeController@attendance')->name('employees.attendance');
    Route::post('/employees/attendance', 'EmployeeController@attendance')->name('employees.attendance');
    Route::post('/employees/attendance/{id}', 'EmployeeController@update_attendance')->name('employees.attendance.terlambat');
    Route::delete('/employees/attendance/{attendance_id}', 'EmployeeController@attendanceDelete')->name('employees.attendance.delete');
    Route::get('/employees/profile/{employee_id}', 'EmployeeController@employeeProfile')->name('employees.profile');
    Route::delete('/employees/{employee_id}', 'EmployeeController@destroy')->name('employees.delete');
    // Routes for employees //

    // Routes for leaves //
    Route::get('/leaves/list-leaves', 'LeaveController@index')->name('leaves.index');
    Route::put('/leaves/{leave_id}', 'LeaveController@update')->name('leaves.update');
    // Routes for leaves //

    // Routes for expenses //
    Route::get('/expenses/list-expenses', 'ExpenseController@index')->name('expenses.index');
    Route::put('/expenses/{expense_id}', 'ExpenseController@update')->name('expenses.update');
    // Routes for expenses //
    // Routes for expenses //
    Route::get('/task', 'TaskController@index')->name('task.index');
    Route::get('/task/create', 'TaskController@create')->name('task.create');
    Route::get('/task/history', 'TaskController@history')->name('task.history');
    Route::get('/task/detail/{id}', 'TaskController@detail')->name('task.detail');
    Route::post('/task/store', 'TaskController@store')->name('task.store');
    Route::post('/task/cancel/{id}', 'TaskController@cancel_task')->name('task.cancel');
    Route::post('/task/approved/{id}', 'TaskController@approved_task')->name('task.approved');
    // Routes for expenses //

    // Routes for holidays //
    Route::get('/holidays/list-holidays', 'HolidayController@index')->name('holidays.index');
    Route::get('/holidays/add-holiday', 'HolidayController@create')->name('holidays.create');
    Route::post('/holidays', 'HolidayController@store')->name('holidays.store');
    Route::get('/holidays/edit-holiday/{holiday_id}', 'HolidayController@edit')->name('holidays.edit');
    Route::put('/holidays/{holiday_id}', 'HolidayController@update')->name('holidays.update');
    Route::delete('/holidays/{holiday_id}', 'HolidayController@destroy')->name('holidays.delete');

    Route::get('/services', 'ServiceController@index')->name('services.index');
    Route::post('/services/store', 'ServiceController@store')->name('services.store');
    Route::post('/services/update/{id}', 'ServiceController@update')->name('services.update');

    Route::get('/positions', 'PositionController@index')->name('positions.index');
    Route::post('/positions/store', 'PositionController@store')->name('positions.store');
    Route::post('/positions/update/{id}', 'PositionController@update')->name('positions.update');

    Route::get('/reimbursements', 'ReimbursementController@index')->name('reimbursements.index');
    // Route::post('/reimbursements/store', 'PositionController@store')->name('reimbursements.store');
    Route::post('/reimbursements/update/{id}', 'ReimbursementController@update')->name('reimbursements.update');
    // Routes for holidays //
});

Route::namespace('Employee')->prefix('employee')->name('employee.')->middleware(['auth', 'can:employee-access'])->group(function () {
    Route::get('/', 'EmployeeController@index')->name('index');
    Route::get('/profile', 'EmployeeController@profile')->name('profile');
    Route::get('/profile-edit/{employee_id}', 'EmployeeController@profile_edit')->name('profile-edit');
    Route::put('/profile/{employee_id}', 'EmployeeController@profile_update')->name('profile-update');

    Route::get('/reset-password', 'EmployeeController@reset_password')->name('reset-password');
    Route::put('/update-password', 'EmployeeController@update_password')->name('update-password');
    // Routes for Attendances //
    Route::get('/attendance/list-attendances', 'AttendanceController@index')->name('attendance.index');
    Route::post('/attendance/list-attendances', 'AttendanceController@index')->name('attendance.index');
    Route::post('/attendance/get-location', 'AttendanceController@location')->name('attendance.get-location');
    Route::get('/attendance/register', 'AttendanceController@create')->name('attendance.create');
    Route::post('/attendance/{employee_id}', 'AttendanceController@store')->name('attendance.store');
    Route::put('/attendance/{attendance_id}', 'AttendanceController@update')->name('attendance.update');
    // Routes for Attendances //
    /*
    *
    */
    // Routes for Leaves //
    Route::get('/leaves/apply', 'LeaveController@create')->name('leaves.create');
    Route::get('/leaves/list-leaves', 'LeaveController@index')->name('leaves.index');
    Route::post('/leaves/{employee_id}', 'LeaveController@store')->name('leaves.store');
    Route::get('/leaves/edit-leave/{leave_id}', 'LeaveController@edit')->name('leaves.edit');
    Route::put('/leaves/{leave_id}', 'LeaveController@update')->name('leaves.update');
    Route::delete('/leaves/{leave_id}', 'LeaveController@destroy')->name('leaves.delete');
    // Routes for Leaves //

    // Routes for Expenses//
    Route::get('/expenses/list-expenses', 'ExpenseController@index')->name('expenses.index');
    Route::get('/expenses/claim-expense', 'ExpenseController@create')->name('expenses.create');
    Route::post('/expenses/{employee_id}', 'ExpenseController@store')->name('expenses.store');
    Route::get('/expenses/edit-expense/{expense_id}', 'ExpenseController@edit')->name('expenses.edit');
    Route::put('/expenses/{expense_id}', 'ExpenseController@update')->name('expenses.update');
    Route::delete('/expenses/{expense_id}', 'ExpenseController@destroy')->name('expenses.delete');
    // Routes for Expenses//

    // Routes for Self //
    Route::get('/self/holidays', 'SelfController@holidays')->name('self.holidays');
    Route::get('/self/salary_slip', 'SelfController@salary_slip')->name('self.salary_slip');
    Route::get('/self/salary_slip_print', 'SelfController@salary_slip_print')->name('self.salary_slip_print');
    // Routes for Self //

    // Routes for Task //
    Route::get('/task', 'TaskController@index')->name('task');
    Route::post('/update-task/{id}', 'TaskController@update_task')->name('task.update');
    Route::post('/update-subtask/{id}', 'TaskController@update_subtask')->name('subtask.update');
    Route::get('/task/history', 'TaskController@history')->name('task.history');
    Route::post('/task/store', 'TaskController@store')->name('task.store');
    Route::post('/subtask/store', 'TaskController@store_subtask')->name('subtask.store');
    Route::get('/task/detail/{id}', 'TaskController@detail')->name('task.detail');

    Route::get('/reimbursements', 'ReimbursementController@index')->name('reimbursements.index');
    Route::post('/reimbursements/store', 'ReimbursementController@store')->name('reimbursements.store');
    Route::post('/reimbursements/update/{id}', 'ReimbursementController@update')->name('reimbursements.update');

});
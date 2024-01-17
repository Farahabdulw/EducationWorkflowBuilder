<?php
// breadcrumbs.php

// Notifications
Breadcrumbs::for('notifications', function ($trail) {
    $trail->push('Notifications', route('notifications'));
});
// Users (Landing Page)
Breadcrumbs::for('users-landing', function ($trail) {
    $trail->push('Users', route('users-list'));
});
// Add User
Breadcrumbs::for('add-user', function ($trail) {
    $trail->parent('users-landing');
    $trail->push('Add User', route('user-add'));
});
// Add Group
Breadcrumbs::for('add-group', function ($trail) {
    $trail->parent('users-landing');
    $trail->push('Add Group', route('user-create-group'));
});
// Edit Group
Breadcrumbs::for('edit-group', function ($trail, $groupId) {
    $trail->parent('users-landing');
    $trail->push('Edit Group', route('user-edit-group', $groupId));
});

// Committees
Breadcrumbs::for('committees', function ($trail) {
    $trail->push('Committees', route('committees-list'));
});
// Add Committee
Breadcrumbs::for('add-committee', function ($trail) {
    $trail->parent('committees');
    $trail->push('Add Committee', route('committees-add'));
});

// Offices
Breadcrumbs::for('offices', function ($trail) {
    $trail->push('Offices', route('offices-list'));
});
// Add Office
Breadcrumbs::for('add-office', function ($trail) {
    $trail->parent('offices');
    $trail->push('Add Office', route('offices-add'));
});

// Colleges
Breadcrumbs::for('colleges', function ($trail) {
    $trail->push('Colleges', route('colleges-list'));
});

// Add College
Breadcrumbs::for('add-college', function ($trail) {
    $trail->parent('colleges');
    $trail->push('Add College', route('colleges-add'));
});

// Departments
Breadcrumbs::for('departments', function ($trail) {
    $trail->push('Departments', route('departments-list'));
});

// Add Department
Breadcrumbs::for('add-department', function ($trail) {
    $trail->parent('departments');
    $trail->push('Add Department', route('departments-add'));
});

// Centers
Breadcrumbs::for('centers', function ($trail) {
    $trail->push('Centers', route('centers-list'));
});

// Add Center
Breadcrumbs::for('add-center', function ($trail) {
    $trail->parent('centers');
    $trail->push('Add Center', route('centers-add'));
});

// Coruses
Breadcrumbs::for('courses', function ($trail) {
    $trail->push('Coruses', route('courses-list'));
});

// Add Course
Breadcrumbs::for('add-course', function ($trail) {
    $trail->parent('courses');
    $trail->push('Add Course', route('courses-add'));
});
// View Course
Breadcrumbs::for('view-course', function ($trail, $cousreId) {
    $trail->parent('courses');
    $trail->push('View Course', route('course-view', $cousreId));
});
// Edit Course
Breadcrumbs::for('edit-course', function ($trail, $cousreId) {
    $trail->parent('courses');
    $trail->push('Edit Course', route('course-edit', $cousreId));
});

// Form requests
Breadcrumbs::for('requests', function ($trail) {
    $trail->parent('forms');
    $trail->push('Requests', route('requests'));
});
// Form requests
Breadcrumbs::for('requests-history', function ($trail) {
    $trail->parent('requests');
    $trail->push('Requests History', route('history'));
});
// Forms
Breadcrumbs::for('forms', function ($trail) {
    $trail->push('Forms', route('forms'));
});

// View Form
Breadcrumbs::for('view-form', function ($trail, $formId) {
    $trail->parent('forms');
    $trail->push('View Form', route('form-get', $formId));
});

// Add Form
Breadcrumbs::for('add-form', function ($trail) {
    $trail->parent('forms');
    $trail->push('Add Form', route('forms-create'));
});

// Edit Form
Breadcrumbs::for('edit-form', function ($trail, $formId) {
    $trail->parent('forms');
    $trail->push('Edit Form', route('form-edit', $formId));
});

// Add Category (from Add Form page)
Breadcrumbs::for('add-category-from-add-form', function ($trail) {
    $trail->parent('add-form');
    $trail->push('Add Category', route('forms-create-category'));
});

// Add Category (from Edit Form page)
Breadcrumbs::for('add-category-from-edit-form', function ($trail, $formId) {
    $trail->parent('edit-form', $formId);
    $trail->push('Add Category', route('forms-create-category'));
});

// register-portal
Breadcrumbs::for('register', function ($trail) {
    $trail->push('Register portal', route('register_portal'));
});

// frequent-used
Breadcrumbs::for('frequent-used', function ($trail) {
    $trail->push('Frequent Used', route('frequent-used'));
});
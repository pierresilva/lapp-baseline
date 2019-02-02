
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($users)->name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="text" id="email" value="{{ old('email', optional($users)->email) }}" minlength="1" maxlength="255" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="col-md-2 control-label">password</label>
    <div class="col-md-10">
        <input class="form-control" name="password" type="text" id="password" value="{{ old('password', optional($users)->password) }}" minlength="1" maxlength="255" required="true" placeholder="Enter password here...">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
    <label for="active" class="col-md-2 control-label">active</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="active_1">
            	<input id="active_1" class="" name="active" type="checkbox" value="1" {{ old('active', optional($users)->active) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
    <label for="avatar" class="col-md-2 control-label">avatar</label>
    <div class="col-md-10">
        <input class="form-control" name="avatar" type="text" id="avatar" value="{{ old('avatar', optional($users)->avatar) }}" minlength="1" maxlength="255" required="true" placeholder="Enter avatar here...">
        {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('permission_user') ? 'has-error' : '' }}">
    <label for="permission_user" class="col-md-2 control-label">permission_user</label>
    <div class="col-md-10">
        <input class="form-control" name="permission_user" type="text" id="permission_user" value="{{ old('permission_user', optional($users)->permission_user) }}" minlength="1" placeholder="Enter permission user here...">
        {!! $errors->first('permission_user', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('role_user') ? 'has-error' : '' }}">
    <label for="role_user" class="col-md-2 control-label">role_user</label>
    <div class="col-md-10">
        <input class="form-control" name="role_user" type="text" id="role_user" value="{{ old('role_user', optional($users)->role_user) }}" minlength="1" placeholder="Enter role user here...">
        {!! $errors->first('role_user', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<select name="countries" id="countries">
    @foreach($countries as $country)
        <option value="{{ $country->code }}">{{ $country->name }}</option>
    @endforeach
</select>

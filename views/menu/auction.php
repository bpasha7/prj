<form id="search_bar" class="form-wrapper cf">
<!--    <h2>
        Аукцион
    </h2>-->
    <input name="name" type="text" placeholder="Попробуем что-нибудь найти?" required>
    <button type="submit">
        Искать
    </button>
    <select class="auction_select" name="group" id="groups">
        <option value="">
            Выберите группу...
        </option>
    </select>
    <select class="auction_select" name="sort">
        <option value="" disabled>
            Выберите сортировку...
        </option>
         <option value="">
            Без соритровки
        </option>
        <option value="ASC">
            Цена &#9650
        </option>
        <option value="DESC">
            Цена &#9660
        </option>
    </select>
</form>
<table class="table_lots" id="auction_lots">
</table>
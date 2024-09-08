<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Document</title>
</head>
<body x-data="main" x-init="fetchData()">
    
    <div>
        <h2>Select Author from List</h2>
        <select 
            x-model="author.author"
            @change="getArticles">
            <option value=""></option>
            <template x-for="item in items">
                <option x-text="item"></option>
            </template>
        </select>
    </div>
<br>
    <hr>
    <div>
        <h2>List of Articles</h2>
        <ul>
            <template x-for="title in articles">
                <li x-text="title"></li>
            </template>
        </ul>
    </div>
</body>
</html>
<script>
    function main(){
        return {
            items:'',
            items:[],
            author:{},
            articles:[],
            loading: false,
            fetchData(){
                let url = '{{ url('authors') }}'
                fetch(url)
                .then(response => response.json())
                .then(response => {
                    if(response.success){
                        this.items = response.data
                    }
                })
            },
            getArticles(){
                let url = '{{ url('articles') }}'
                fetch(url, {
                    method:'POST',
                    body: JSON.stringify(this.author),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': "{{csrf_token()}}",
                    },
                })
                .then( response => response.json())
                .then( response => {
                    if(response.success){
                        this.articles = response.data
                    }
                })
            }
        }
    }
</script>
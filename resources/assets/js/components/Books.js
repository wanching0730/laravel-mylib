import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Books extends Component {
    constructor(props) {
        super(props)
            this.state = { books: null } 
    }

    componentDidMount() {
        let url = '/api/books'
        fetch(url, { headers: {
        Accept: 'application/json', },
        credentials: 'same-origin', 
        })
        .then((response) => {
            if(!response.ok) throw Error([response.status, response.statusText].join(' '))
            return response.json() 
        })
        .then((body) => { this.setState({
            books: body.data, })
        })
        .catch((error) => alert(error)) 
    }

    render() {
        const { books } = this.state;

        let content

        if(!books) {
            content = (
                <p>Loading data...</p>
            )
        } else if(books.length == 0) {
            content = (
                <p>No books in record</p>
            )
        } else {
            let items = books.map((book) => 
                <tr key={book.id}>
                    <td>{book.isbn}</td>
                    <td>{book.title}</td>
                    <td>{book.year}</td>
                    <td>{book.synopsis}</td>
                </tr>
            )

        content = (
            <div className="table-responsive">
                <table className="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Synopsis</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items}
                    </tbody>
                </table>
            </div>)
        }
        
        return (
            <div className="content-wrapper">
                {content}
            </div>
        );
    }
}

(() => {
    let element = document.getElementById('content-books')

    if(element) {
        ReactDOM.render(<Books />, element);
    }
})()

// if (document.getElementById('example')) {
//     ReactDOM.render(<Example />, document.getElementById('example'));
// }

import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Example extends Component {
    render() {
        return (
            <div className="max-w-2xl rounded bg-white border border-gray-400 mx-auto overflow-hidden">
                <div className="px-5 py-4 bg-gray-200 border-b border-gray-400 text-sm">
                    Example Component
                </div>

                <div className="px-5 py-5 text-sm">I'm an example component.</div>
            </div>
        );
    }
}

if (document.getElementById('example')) {
    ReactDOM.render(<Example />, document.getElementById('example'));
}

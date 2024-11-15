export default function Alerts({ msg, color }) {
    if (msg && color) {
        const classMsg = `alert alert-${color} alert-dismissible fade show`
        return <div className={classMsg} role="alert">
            {msg}
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    }
    return <div></div>
}
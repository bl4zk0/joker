export default {
    data() {
        return {
            ka: {
                "No table found": "მაგიდა არ მოიძებნა",
                "Only 9": "9-იანები",
                "Standard": "სტანდარტული",
                "Join": "შესვლა",
                "Trump": "კოზირი",
                "Kick": "გაგდება",
                "Start": "დაწყება",
                "None": "ბეზი",
                "Please wait for other players": "დაელოდე მოთამაშეების თანხმობას",
                "Are you ready?": "მზად ხარ?",
                "Yes": "დიახ",
                "No": "არა",
                "Take": "მოჯოკრა",
                "Give": "ქვევიდან",
                "High": "მაღალი",
                "Give to": "წაიღოს",
                "Pin code": "პინ კოდი",
                "Game Over": "თამაში დასრულდა",
                "You have been kicked": "თქვენ გამოგაგდეს მაგიდიდან",
                "To clear the chat type \"clear\"": "ჩათის გასასუფთავებლად დაწერე \"clear\"",
                "Start Game": "თამაშის დაწყება",
                "Position": "პოზიცია",
                "Cards": "კარტები",
                "Cheat": "ჩაწყობა",
                "Loading": "მიმდინარეობს ჩატვირთვა",
                "Enter pin code": "შეიყვანეთ პინ კოდი",
                "Invalid pin code": "პინ კოდი არასწორია",
                "Notification": "შეტყობინება",
                "Player": "მოთამაშე"
            }
        }
    },

    methods: {
        lang(text) {
            let locale = document.cookie.split(';')
                .filter((val) => {return val.trim().startsWith('lang=')})[0].slice(-2);
                        
            if (locale === 'en' || !locale) return text;
            
            if (locale === 'ka') {
                return this.ka[text];
            }
        }
    }
}
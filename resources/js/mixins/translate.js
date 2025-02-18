export default {
    data() {
        return {
            ka: Object.freeze({
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
                "Jokero": "მოჯოკრა",
                "Fold": "ქვევიდან",
                "High": "მაღალი",
                "Takero": "წაიღოს",
                "Pin code": "პინ კოდი",
                "GAME OVER": "თამაში დასრულდა",
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
                "Player": "მოთამაშე",
                "Joined": "შემოვიდა",
                "Left": "გავიდა",
                "Search": "ძებნა",
                "No Emoji Found": "ემოჯი არ მოიძებნა",
                "Search Results": "ძიების შედეგი",
                "Frequently Used": "ხშირად გამოყენებული",
                "Smileys & Emoticon": "სმაილები & ემოციები",
                "People & Body": "ხალხი & სხეული",
                "Animals & Nature": "ფლორა & ფაუნა",
                "Food & Drink": "საკვები & სასმელი",
                "Activity": "აქტივობები",
                "Travel & Places": "მოგზაურობა & ადგილები",
                "Objects": "ობიექტები",
                "Symbols": "სიმბოლები",
                "Flags": "დროშები",
                "Lobby": "ოთახები"
            })
        }
    },

    methods: {
        lang(text) {
            //let locale = document.cookie.split(';')
            //.filter((val) => {return val.trim().startsWith('lang=')})[0].slice(-2);
            let locale = App.locale;
            if (locale === 'en' || !locale) return text;

            if (locale === 'ka') {
                return this.ka[text];
            }
        }
    }
}
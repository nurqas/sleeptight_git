class SleepApneaChatbot {
    constructor() {
        this.qa_dict = {
            "What is sleep apnea?": "Sleep apnea is a serious sleep disorder where breathing repeatedly stops and starts.",
            "What are the symptoms of sleep apnea?": "Symptoms include loud snoring, episodes of stopped breathing during sleep, abrupt awakenings accompanied by gasping or choking, and excessive daytime sleepiness.",
            "What causes sleep apnea?": "Causes include obesity, large tonsils, a narrow airway, and certain medical conditions. It can also be hereditary.",
            "How is sleep apnea diagnosed?": "Sleep apnea is typically diagnosed with a sleep study, either at home or in a sleep lab, where your breathing and other body functions are monitored during sleep.",
            "What are the treatments for sleep apnea?": "Treatments include lifestyle changes, continuous positive airway pressure (CPAP) therapy, oral appliances, and in some cases, surgery.",
            "Can sleep apnea be cured?": "There is no cure for sleep apnea, but it can be effectively managed with the appropriate treatment.",
            "Is sleep apnea dangerous?": "Yes, if left untreated, sleep apnea can lead to serious health problems such as high blood pressure, heart disease, stroke, and diabetes."
        };
    }
    
    getAnswer(question) {
        return this.qa_dict[question] || "I'm sorry, I don't have an answer for that question.";
    }
}

const chatbot = new SleepApneaChatbot();

function sendMessage() {
    const userInput = document.getElementById('user-input').value;
    if (!userInput) return;

    appendMessage(userInput, 'user');

    const botResponse = chatbot.getAnswer(userInput);
    setTimeout(() => {
        appendMessage(botResponse, 'bot');
        document.getElementById('user-input').value = '';
    }, 500);
}

function appendMessage(message, sender) {
    const chatBox = document.getElementById('chat-box');
    const messageElement = document.createElement('div');
    messageElement.className = `message ${sender}`;
    messageElement.textContent = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function openChatbotModal() {
    document.getElementById('chatbotModal').style.display = 'flex';
}

function closeChatbotModal() {
    document.getElementById('chatbotModal').style.display = 'none';
}

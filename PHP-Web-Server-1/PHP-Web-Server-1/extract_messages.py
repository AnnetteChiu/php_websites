
import json

def extract_messages_as_items():
    """简单提取消息并分配item ID"""
    
    try:
        # 读取原始数据
        with open('submissions.json', 'r', encoding='utf-8') as file:
            data = json.load(file)
        
        # 提取消息并创建item映射
        message_items = {}
        
        for i, submission in enumerate(data, 1):
            item_id = f"item_{i:03d}"
            message_items[item_id] = {
                "id": item_id,
                "message": submission.get('message', ''),
                "source_user_id": submission.get('user_id', ''),
                "author": submission.get('name', ''),
                "created_at": submission.get('timestamp', '')
            }
        
        # 保存提取的消息
        with open('extracted_messages.json', 'w', encoding='utf-8') as file:
            json.dump(message_items, file, ensure_ascii=False, indent=2)
        
        # 显示结果
        print("📝 消息提取完成!")
        print(f"📊 共提取 {len(message_items)} 条消息")
        
        for item_id, item in message_items.items():
            print(f"{item_id}: {item['message']} (作者: {item['author']})")
            
    except FileNotFoundError:
        print("❌ 找不到 submissions.json 文件")
    except Exception as e:
        print(f"❌ 错误: {e}")

if __name__ == "__main__":
    extract_messages_as_items()
